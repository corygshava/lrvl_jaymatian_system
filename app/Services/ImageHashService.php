<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageHashService{
	protected ImageManager $imgman;
	protected $imgscale = 128;

    public function __construct(){
        $this->imgman = new ImageManager(new Driver);
    }

	public function generateHash(UploadedFile | string $fyl,&$errl = []) : array {
		// try{
			$px = $this->imgscale;
			$img = $this->imgman->read($fyl);
			$img->resize($px,$px);
			$img->greyscale();

			$pixels = [7];
			$cnt = 0;

			for($y = 0;$y < $px;$y++){
				for($x = 0;$x < $px;$x++){
					$col = $img->pickColor($x, $y);

					$r = $col->red()->toInt();
					$g = $col->green()->toInt();
					$b = $col->blue()->toInt();
					$a = $col->alpha()->toInt();

					if($cnt < 40){
						// $errl[] = "{$r}, {$g}, {$b}, {$a}";
						$cnt += 1;
					}

					$pixels[] = ($r + $g + $b) / 3;
				}
			}

			$errl[] = 'fetched color values';

			$avg = array_sum($pixels) / count($pixels);
			$errl[] = "got average pixel radiance ($avg)";

			$mybin = '';
			foreach($pixels as $p){
				$mybin .= $p > $avg ? '1' : '0';
			}

			// $errl[] = "binary: $mybin";
			$ccc = strlen($mybin);
			$dd = $ccc / 4;
			$errl[] = "bits: {$ccc}, div_by_4: {$dd}";

			$myhex = '';
			$myhex = $this->binaryToHex($mybin, $errl);

			return [
				'hex' => $myhex,
				'binary' => $mybin,
			];
		// } catch(\Exception $err){
			$errl[] = $err;

			return [
				'hex' => '',
				'binary' => '',
			];
		// }
	}

	public function binaryToHex(string $bin,&$err = []): string{
		$hex = '';
		$f_bin = str_pad($bin, ceil(strlen($bin) / 4) * 4, '0', STR_PAD_LEFT);

		$b_org = strlen($bin);
		$b_cnt = strlen($f_bin);
		$added = $b_cnt - $b_org;
		$err[] = "fixed bin legnth: {$b_cnt}, original: {$b_org}, added: {$added}";

		$hex = bin2hex($f_bin);
		$err[] = 'internal hexmap complete';
		// $err[] = $hex;

		$hxl = strlen($hex);
		$err[] = "length: $hxl";

		$hex = '';
		for($x = 0;$x < $b_cnt;$x += 4){
			$chunk = substr($f_bin, $x, 4);
			$decval = bindec($chunk);
			$hex .= dechex($decval);
		}

		$err[] = 'hexmap recalculated';
		// $err[] = $hex;
		$hxl = strlen($hex);
		$err[] = "length: $hxl";

		return $hex;
	}
}
