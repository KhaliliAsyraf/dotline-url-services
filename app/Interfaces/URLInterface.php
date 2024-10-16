<?php

namespace App\Interfaces;

interface URLInterface
{
    public function storeURL(string $url);
    public function getOriginalURL(string $url);
}
