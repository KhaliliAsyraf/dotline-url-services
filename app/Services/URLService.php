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
        $url = URL::firstOrCreate(
                [
                    'original_url' => $url,
                ],
                [
                    'shorten_url' => $this->generateShortenURL(),
                    'expired_date' => Carbon::now()
                        ->addDays(URLEnum::URL_VALIDITY->value)
                        ->toDateTimeString()
                ]
            );

        return $url->fullShortenURL;
    }
          
    /**
     * getOriginalURL
     *
     * @param  string $url
     * @param  string $ip
     * @return string
     */
    public function getOriginalURL(string $url, string $ip): string
    {
        $originalURL = URL::whereShortURL($url)->first();
        
        if (!$this->verifyURLExpiry($originalURL->expired_date)) {
            throw new \Exception('URL was already expired!');
        }

        $this->storeAccessedURLTimestamp($originalURL->id, $ip); // To store accessed url time
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
        return URL::select('id', 'shorten_url', 'original_url', 'description')
            ->with(
                [
                    'accessedURLInfo' => function ($query) {
                        $query->select('id_urls', 'created_at as accessed_at', 'location');
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
                                return $info->only(['accessed_at', 'location']);
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
     * @return void
     */
    public function storeAccessedURLTimestamp(int $urlId, $ip): void
    {
        URLAccessedInfo::create(
                [
                    'id_urls' => $urlId,
                    'location' => $this->getLocationInfoBasedOnIP($ip)
                ]
            );
    }
}