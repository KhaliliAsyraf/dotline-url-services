<?php

namespace App\Services;

use App\Enums\URLEnum;
use App\Interfaces\URLInterface;
use App\Models\URL;
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
     * @return string
     */
    public function getOriginalURL(string $url): string
    {
        $originalURL = URL::whereShortURL($url)->first();
        
        if (!$this->verifyURLExpiry($originalURL->expired_date)) {
            throw new \Exception('URL was already expired!');
        }

        $this->trackAccessedCountOfURL($originalURL); // To track count no. of accessed of the URL
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
        return URL::when(
                $url,
                function($query) use ($url) {
                    $query->where('shorten_url', $url);
                }
            )
            ->get()
            ->transform(
                function ($url) {
                    $url->shorten_url = $url->fullShortenURL;
                    return $url->only(
                        [
                            'shorten_url',
                            'no_of_accessed',
                            'original_url',
                            'description'
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
     * To track accessed count of specified URL
     *
     * @param  string $url
     * @return void
     */
    public function trackAccessedCountOfURL(string $url): void
    {
        URL::whereShortURL($url)->increment('no_of_accessed');
    }
}