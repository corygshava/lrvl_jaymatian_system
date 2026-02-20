<?php

namespace App\Http\Controllers;

use App\Models\SavedReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;

class ReportVerificationController extends Controller
{
    protected $hasher;

    public function __construct(){
        // Initialize perceptual hasher
        $this->hasher = new ImageHash(new DifferenceHash());
    }

    /**
     * Save a report file and create a database record
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveReportFile(Request $request){
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        
        // Generate perceptual hash
        $imageHash = $this->hasher->hash($file->getRealPath());
        
        // Generate unique filename
        $filename = uniqid('report_') . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        // Store file in storage/app/runtime/reports_genai
        $path = $file->storeAs('runtime/reports_genai', $filename);
        
        // Create database record
        $savedReport = SavedReport::create([
            'img_path' => $path,
            'img_hash' => $imageHash->toHex(), // Store as hex string
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Report file saved successfully',
            'data' => [
                'id' => $savedReport->id,
                'img_path' => $savedReport->img_path,
                'img_hash' => $savedReport->img_hash,
            ]
        ], 201);
    }

    /**
     * Verify if an uploaded image exists in database for a specific date
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyReportFile(Request $request){
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'date' => 'required|date', // Format: Y-m-d
        ]);

        $file = $request->file('file');
        $date = $request->input('date');
        
        // Generate hash of uploaded file
        $uploadedHash = $this->hasher->hash($file->getRealPath());
        
        // Query database for matching hash on the specified date
        $exists = SavedReport::whereDate('created_at', $date)
            ->where('img_hash', $uploadedHash->toHex())
            ->exists();

        return response()->json([
            'success' => true,
            'found' => $exists,
            'date' => $date,
            'message' => $exists 
                ? 'Report file found in database for the specified date' 
                : 'Report file not found in database for the specified date'
        ]);
    }

    /**
     * Alternative: Verify with similarity threshold (allows minor differences)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyReportFileSimilar(Request $request){
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'date' => 'required|date',
            'threshold' => 'nullable|integer|min:0|max:20', // Hamming distance threshold
        ]);

        $file = $request->file('file');
        $date = $request->input('date');
        $threshold = $request->input('threshold', 5); // Default threshold
        
        // Generate hash of uploaded file
        $uploadedHash = $this->hasher->hash($file->getRealPath());
        
        // Get all reports from the specified date
        $reports = SavedReport::whereDate('created_at', $date)->get();
        
        $found = false;
        $matchedReport = null;
        
        foreach ($reports as $report) {
            // Calculate Hamming distance between hashes
            $distance = $this->hasher->distance(
                $uploadedHash,
                $this->hasher->hash(Storage::path($report->img_path))
            );
            
            if ($distance <= $threshold) {
                $found = true;
                $matchedReport = [
                    'id' => $report->id,
                    'img_path' => $report->img_path,
                    'distance' => $distance,
                ];
                break;
            }
        }

        return response()->json([
            'success' => true,
            'found' => $found,
            'date' => $date,
            'threshold' => $threshold,
            'match' => $matchedReport,
            'message' => $found 
                ? 'Similar report file found in database for the specified date' 
                : 'No similar report file found in database for the specified date'
        ]);
    }
}