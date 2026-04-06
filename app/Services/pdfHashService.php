<?php

namespace App\services;

use App\Services\ImageHashService;
use Illuminate\Http\UploadedFile;
use Spatie\PdfToImage\Pdf;

class pdfHashService
{
    protected ImageHashService $imghasher;

    public function __construct(){
        $this->imghasher = new ImageHashService();
    }

	public function generateHash(UploadedFile $fyl,&$errl = []) {
		try{
			// $pages = $this->getPageCount($fyl);
			$tmpImg = $this->convertPageToImage($fyl,1);
			$thehash = $this->imghasher->generateHash($tmpImg);

			if(file_exists($tmpImg)){
				unlink($tmpImg);
			}

			return $thehash;
		} catch (\Exception $e){
			$errl[] = 'there was an error hashing the pdf';
			$errl[] = $e;

			return [
				'hex' => '',
				'binary' => '',
			];
		}
	}

	private function convertPageToImage(UploadedFile $fyl, int $page = 1){
		$tmppdf = sys_get_temp_dir().'/'.uniqid('pdf_').'.pdf';
		$tmpimgp = sys_get_temp_dir().'/'.uniqid('pdf_img_').'.jpg';
		file_put_contents($tmppdf, $fyl->get());

		$pdf = new Pdf($tmppdf);
		$pdf->setPage($page)
			->setResolution(150)
			->setOutputFormat('jpg')
			->saveImage($tmpimgp)
		;

		if(file_exists($tmppdf)){
			unlink($tmppdf);
		}

		return $tmpimgp;
	}
}
