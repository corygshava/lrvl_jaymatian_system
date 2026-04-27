<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait TrackLastUpdater
{
    protected static function bootTrackLastUpdater(): void{
		static::updating(function ($model){
			if(Auth::user() !== null){
				$model->last_updated_by = Auth::id();
			}
		});
	}
}
