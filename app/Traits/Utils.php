<?php

namespace App\Traits;

trait Utils {
    /**
     * use this to find the collumns that can be modified by a user
     *
     * @return array
     */
	public function getCols(): array{
		return $this->fillable;
	}

    /**
     * use this to find how columns are cast
     *
     * @return array
     */
	public function getCasts(): array{
		return $this->casts;
	}
}
