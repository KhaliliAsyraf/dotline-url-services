<?php

namespace App\Traits;

use Carbon\Carbon;

trait URLTrait
{
    public function verifyURLExpiry(string $expiryDate): bool
    {
        return Carbon::now() > Carbon::parse($expiryDate);
    }
}