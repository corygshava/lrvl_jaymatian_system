<?php

namespace App\Http\Controllers;

use App\Models\SavedReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use Imagick;

class PdfVerificationController extends Controller
{
    /**
     * Save a PDF with multiple hash signatures
     */
    public function savePdfReport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:20480', // 20MB max
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();
        
        // Generate all hash types
        $hashes = $this->generatePdfHashes($filePath);
        
        // Generate unique filename
        $filename = uniqid('report_') . '_' . time() . '.pdf';
        
        // Store file
        $path = $file->storeAs('runtime/reports_genai', $filename);
        
        // Create database record with all hashes
        $savedReport = SavedReport::create([
            'img_path' => $path,
            'file_type' => 'application/pdf',
            
            // Bytecode hashes
            'hash_bytecode_header' => $hashes['bytecode_header'],
            'hash_bytecode_full' => $hashes['bytecode_full'],
            
            // Content hashes
            'hash_text_content' => $hashes['text_content'],
            'hash_visual_content' => $hashes['visual_content'],
            
            // Metadata
            'pdf_type' => $hashes['pdf_type'], // 'text', 'image', or 'mixed'
            'page_count' => $hashes['page_count'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'PDF report saved successfully',
            'data' => [
                'id' => $savedReport->id,
                'img_path' => $savedReport->img_path,
                'pdf_type' => $hashes['pdf_type'],
                'page_count' => $hashes['page_count'],
                'hashes' => [
                    'bytecode_header' => substr($hashes['bytecode_header'], 0, 16) . '...',
                    'bytecode_full' => substr($hashes['bytecode_full'], 0, 16) . '...',
                    'text_content' => substr($hashes['text_content'], 0, 16) . '...',
                    'visual_content' => substr($hashes['visual_content'], 0, 16) . '...',
                ],
            ]
        ], 201);
    }

    /**
     * Verify PDF with multiple comparison methods
     */
    public function verifyPdfReport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:20480',
            'date' => 'required|date',
            'strict' => 'nullable|boolean', // true = exact bytecode match, false = content match
        ]);

        $file = $request->file('file');
        $date = $request->input('date');
        $strict = $request->input('strict', false);
        
        // Generate hashes for uploaded file
        $uploadedHashes = $this->generatePdfHashes($file->getRealPath());
        
        // Get all PDFs from the specified date
        $reports = SavedReport::whereDate('created_at', $date)
            ->where('file_type', 'application/pdf')
            ->get();
        
        $matches = [];
        
        foreach ($reports as $report) {
            $matchResult = $this->comparePdfHashes($uploadedHashes, $report, $strict);
            
            if ($matchResult['is_match']) {
                $matches[] = [
                    'id' => $report->id,
                    'img_path' => $report->img_path,
                    'confidence' => $matchResult['confidence'],
                    'match_type' => $matchResult['match_type'],
                    'details' => $matchResult['details'],
                ];
            }
        }

        return response()->json([
            'success' => true,
            'found' => count($matches) > 0,
            'date' => $date,
            'strict_mode' => $strict,
            'uploaded_pdf_type' => $uploadedHashes['pdf_type'],
            'matches' => $matches,
            'message' => count($matches) > 0
                ? count($matches) . ' matching PDF(s) found in database for the specified date'
                : 'No matching PDFs found in database for the specified date'
        ]);
    }

    /**
     * Generate all hash types for a PDF
     */
    private function generatePdfHashes(string $filePath): array
    {
        $fileContent = file_get_contents($filePath);
        
        $hashes = [
            // Bytecode hashes
            'bytecode_header' => hash('sha256', substr($fileContent, 0, 200)),
            'bytecode_full' => hash('sha256', $fileContent),
            
            // Initialize content hashes
            'text_content' => null,
            'visual_content' => null,
            
            // Metadata
            'pdf_type' => 'unknown',
            'page_count' => 0,
        ];
        
        // Try to parse PDF for text content
        try {
            $parser = new Parser();
            $pdf = $parser->parseContent($fileContent);
            
            $text = $pdf->getText();
            $pages = $pdf->getPages();
            $hashes['page_count'] = count($pages);
            
            // Determine PDF type and hash text content
            $textLength = strlen(trim($text));
            
            if ($textLength > 100) {
                // Text-based PDF
                $hashes['pdf_type'] = 'text';
                $hashes['text_content'] = hash('sha256', $this->normalizeText($text));
            } else {
                // Likely image-based PDF
                $hashes['pdf_type'] = 'image';
            }
            
        } catch (\Exception $e) {
            // Parser failed, treat as image-based
            $hashes['pdf_type'] = 'image';
        }
        
        // For image-based PDFs, generate visual hash
        if ($hashes['pdf_type'] === 'image' || $hashes['pdf_type'] === 'unknown') {
            $hashes['visual_content'] = $this->generateVisualHash($filePath);
        }
        
        return $hashes;
    }

    /**
     * Generate perceptual hash from PDF pages as images
     */
    private function generateVisualHash(string $pdfPath): ?string
    {
        try {
            // Check if Imagick is available
            if (!extension_loaded('imagick')) {
                return null;
            }
            
            $imagick = new Imagick();
            $imagick->setResolution(150, 150); // Lower resolution for faster processing
            
            // Read first page only for hash (or adjust for multiple pages)
            $imagick->readImage($pdfPath . '[0]'); // [0] = first page
            $imagick->setImageFormat('png');
            
            // Resize to small size for perceptual hashing
            $imagick->resizeImage(16, 16, Imagick::FILTER_LANCZOS, 1);
            $imagick->setImageType(Imagick::IMGTYPE_GRAYSCALE);
            
            // Get pixel data
            $pixelData = '';
            for ($y = 0; $y < 16; $y++) {
                for ($x = 0; $x < 16; $x++) {
                    $pixel = $imagick->getImagePixelColor($x, $y);
                    $colors = $pixel->getColor();
                    $pixelData .= chr($colors['r']); // Grayscale, so r=g=b
                }
            }
            
            $imagick->clear();
            $imagick->destroy();
            
            return hash('sha256', $pixelData);
            
        } catch (\Exception $e) {
            // Imagick not available or error
            return null;
        }
    }

    /**
     * Compare uploaded PDF hashes with stored PDF
     */
    private function comparePdfHashes(array $uploadedHashes, SavedReport $report, bool $strict): array
    {
        $result = [
            'is_match' => false,
            'confidence' => 0,
            'match_type' => 'none',
            'details' => [],
        ];
        
        // Strict mode: exact bytecode match
        if ($strict) {
            if ($uploadedHashes['bytecode_full'] === $report->hash_bytecode_full) {
                $result['is_match'] = true;
                $result['confidence'] = 100;
                $result['match_type'] = 'exact_bytecode';
                $result['details']['bytecode_full'] = 'exact match';
                return $result;
            }
            
            if ($uploadedHashes['bytecode_header'] === $report->hash_bytecode_header) {
                $result['is_match'] = true;
                $result['confidence'] = 85;
                $result['match_type'] = 'header_bytecode';
                $result['details']['bytecode_header'] = 'exact match';
                return $result;
            }
            
            return $result; // No match in strict mode
        }
        
        // Content mode: flexible matching
        $matches = 0;
        $maxMatches = 0;
        
        // Check bytecode full match (highest confidence)
        $maxMatches++;
        if ($uploadedHashes['bytecode_full'] === $report->hash_bytecode_full) {
            $matches++;
            $result['details']['bytecode_full'] = 'match';
        }
        
        // Check bytecode header
        $maxMatches++;
        if ($uploadedHashes['bytecode_header'] === $report->hash_bytecode_header) {
            $matches++;
            $result['details']['bytecode_header'] = 'match';
        }
        
        // Check text content (for text-based PDFs)
        if ($uploadedHashes['text_content'] && $report->hash_text_content) {
            $maxMatches++;
            if ($uploadedHashes['text_content'] === $report->hash_text_content) {
                $matches++;
                $result['details']['text_content'] = 'match';
            }
        }
        
        // Check visual content (for image-based PDFs)
        if ($uploadedHashes['visual_content'] && $report->hash_visual_content) {
            $maxMatches++;
            if ($uploadedHashes['visual_content'] === $report->hash_visual_content) {
                $matches++;
                $result['details']['visual_content'] = 'match';
            }
        }
        
        // Calculate confidence
        if ($matches > 0 && $maxMatches > 0) {
            $result['confidence'] = round(($matches / $maxMatches) * 100);
            
            // Determine match type
            if ($matches >= $maxMatches) {
                $result['is_match'] = true;
                $result['match_type'] = 'perfect_content';
            } elseif ($matches >= 2) {
                $result['is_match'] = true;
                $result['match_type'] = 'high_confidence';
            } elseif ($matches >= 1 && $result['confidence'] >= 50) {
                $result['is_match'] = true;
                $result['match_type'] = 'probable';
            }
        }
        
        return $result;
    }

    /**
     * Normalize text for consistent hashing
     */
    private function normalizeText(string $text): string
    {
        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        
        // Convert to lowercase
        $text = strtolower($text);
        
        // Trim
        $text = trim($text);
        
        return $text;
    }

    /**
     * Get detailed PDF info for debugging
     */
    public function getPdfInfo(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file');
        $hashes = $this->generatePdfHashes($file->getRealPath());
        
        return response()->json([
            'success' => true,
            'data' => $hashes,
        ]);
    }
}