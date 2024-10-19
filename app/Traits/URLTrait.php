<?php

namespace App\Traits;

use App\Enums\URLEnum;
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
    
    /**
     * getLocationInfoBasedOnIP
     *
     * @param  string $ip
     * @return string
     */
    public function getLocationInfoBasedOnIP(string $ip): string
    {
        $response = file_get_contents(str_replace('{ip}', $ip, URLEnum::IP_INFO_URL->value));
        return $this->concatLocationInfo(json_decode($response, true));
    }
    
    /**
     * concatLocationInfo
     *
     * @param  array $location
     * @param  string $concatLocation
     * @return string
     */
    public function concatLocationInfo(array $location = [], string $concatLocation = ''): string
    {
        $location = Arr::only($location,
                [
                    URLEnum::IP_CITY->value,
                    URLEnum::IP_REGION->value,
                    URLEnum::IP_COUNTRY->value
                ]
            );
        foreach ($location as $key => $info) {
            $key = ucwords($key);
            $concatLocation .= " {$key}: {$info}.";
        }
        return trim($concatLocation);
    }
}