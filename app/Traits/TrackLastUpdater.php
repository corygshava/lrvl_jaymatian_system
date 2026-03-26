<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait TrackLastUpdater
{
    protected static function bootTrackLastUpdater(): void{
		static::updating(function ($model){
			$model->last_updated_by = Auth::id();
		});
	}
}
