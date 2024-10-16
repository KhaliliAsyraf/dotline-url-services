<?php

namespace App\Services;

use App\Interfaces\URLInterface;
use App\Models\URL;
use Illuminate\Support\Str;

class URLService implements URLInterface
{
    /**
     * To store original URL. Pass the shorten URL
     *
     * @param  string $url
     * @return string
     */
    public function storeURL(string $url): string
    {
        $shortenURL = $this->generateShortenURL();

        URL::firstOrCreate(
                [
                    'shorten_url' => $shortenURL
                ],
                [
                    'shorten_url' => $shortenURL,
                    'original_url' => $url
                ]
            );

        return route(
                'redirect',
                [
                    'url' => $shortenURL
                ]
            );
    }
        
    /**
     * getOriginalURL
     *
     * @param  string $url
     * @return string
     */
    public function getOriginalURL(string $url): string
    {
        $this->trackAccessedCountOfURL($url);
        return URL::whereShortURL($url)->first()->original_url;
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