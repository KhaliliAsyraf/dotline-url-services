<?php

namespace App\Interfaces;

interface URLInterface
{
    public function storeURL(string $url);
    public function getOriginalURL(string $url, string $ip);
    public function getAnalyticData(?string $url);
}
