<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Arr;

trait URLTrait
{    
    /**
     * To verifyURLExpiry
     *
     * @param  string $expiryDate
     * @return bool
     */
    public function verifyURLExpiry(string $expiryDate): bool
    {
        return Carbon::parse($expiryDate) > Carbon::now();
    }

    public function getLocationInfoBasedOnIP(string $ip): string
    {
        $response = file_get_contents("http://ipinfo.io/{$ip}/json"); // To put on enum
        return $this->concatLocationInfo(json_decode($response, true));
    }

    public function concatLocationInfo(array $location = [], string $concatLocation = ''): string
    {
        $location = Arr::only($location, ['city', 'region', 'country']); // To put on enum
        foreach ($location as $key => $info) {
            $key = ucwords($key);
            $concatLocation .= " {$key}: {$info}.";
        }
        return trim($concatLocation);
    }
}