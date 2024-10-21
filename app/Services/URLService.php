<?php

namespace App\Services;

use App\Enums\URLEnum;
use App\Interfaces\URLInterface;
use App\Models\URL;
use App\Models\URLAccessedInfo;
use App\Traits\URLTrait;
use Carbon\Carbon;
use Illuminate\Support\Str;

class URLService implements URLInterface
{
    use URLTrait;

    /**
     * To store original URL. Pass the shorten URL
     *
     * @param  string $url
     * @return string
     */
    public function storeURL(string $url): string
    {
        // Only create new shorten url if original URL don't exist, else return existing shorten url
        // Also checkout rateLimiter middleware setup for the storeUrl endpoint :)
        $url = URL::firstOrCreate(
                [
                    'original_url' => $url,
                ],
                [
                    'shorten_url' => $this->generateShortenURL(),
                    'expired_date' => Carbon::now()
                        ->addDays(URLEnum::URL_VALIDITY->value) // Using Enum instead of direct hardcoded
                        ->toDateTimeString()
                ]
            );

        return $url->fullShortenURL; // Using URL attribute
    }
          
    /**
     * getOriginalURL
     *
     * @param  string $url
     * @param  string $ip
     * @param  string $browser
     * @return string
     */
    public function getOriginalURL(string $url, string $ip, string $browser): string
    {
        $originalURL = URL::whereShortURL($url)->first();
        
        // $this->verifyURLExpiry() put under URLTrait to make it single responsibility as much as can
        if (!$this->verifyURLExpiry($originalURL->expired_date)) {
            throw new \Exception('URL was already expired!');
        }

        $this->storeAccessedURLTimestamp($originalURL->id, $ip, $browser); // To store accessed url time
        return $originalURL->original_url;
    }
    
    /**
     * getAnalyticData
     *
     * @param  string|null $url
     * @return array
     */
    public function getAnalyticData(?string $url = null): array
    {
        // Only select certain column for memory optimization
        return URL::select('id', 'shorten_url', 'original_url', 'description')
            ->with(
                [
                    'accessedURLInfo' => function ($query) {
                        // Same goes for selecting certain column of eager load data
                        $query->select('id_urls', 'ip_address', 'created_at as accessed_at', 'location', 'browser');
                    }
                ]
            )
            ->when(
                $url,
                function($query) use ($url) {
                    $query->where('shorten_url', $url);
                }
            )
            ->get()
            ->transform(
                function ($url) {
                    $url->shorten_url = $url->fullShortenURL;
                    $url->count_accessed = $url->accessedURLInfo->count();
                    $url->accessedURLInfo = $url->accessedURLInfo
                        ->transform(
                            function ($info) {
                                return $info->only(
                                    [
                                        'ip_address',
                                        'location',
                                        'browser',
                                        'accessed_at'
                                    ]
                                );
                            }
                        );
                    return $url->only(
                        [
                            'shorten_url',
                            'original_url',
                            'description',
                            'count_accessed',
                            'accessedURLInfo'
                        ]
                    );
                }
            )
            ->toArray();
    }
    
    /**
     * generateShortenURL
     *
     * @return string
     */
    public function generateShortenURL(): string
    {
        return Str::random(6);
    }
    
    /**
     * To store accessed timestamp of specified URL
     *
     * @param  int $urlId
     * @param  string $ip
     * @param  string $browser
     * @return void
     */
    public function storeAccessedURLTimestamp(int $urlId, string $ip, string $browser): void
    {
        URLAccessedInfo::create(
                [
                    'id_urls' => $urlId,
                    'ip_address' => $ip,
                    'location' => $this->getLocationInfoBasedOnIP($ip), // Coming from URLTrait
                    'browser' => $browser
                ]
            );
    }
}