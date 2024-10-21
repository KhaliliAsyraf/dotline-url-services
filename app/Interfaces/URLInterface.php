<?php

namespace App\Interfaces;

interface URLInterface
{
    public function storeURL(string $url);
    public function getOriginalURL(string $url, string $ip, string $browser);
    public function getAnalyticData(?string $url);
}
