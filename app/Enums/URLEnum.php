<?php

namespace App\Enums;

enum URLEnum: string
{
    case URL_VALIDITY = '30'; // Expired in 30 days

    // --- IP Info response params ---
    case IP_CITY = 'city';
    case IP_REGION = 'region';
    case IP_COUNTRY = 'country';

    // Better to put on env instead of enum/const but to make it easier for assessment, so I just put it here
    case IP_INFO_URL = "http://ipinfo.io/{ip}/json";
}