<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreURLRequest;
use App\Interfaces\URLInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class URLController extends BaseController
{
    public function __construct(protected URLInterface $urlServices)
    {
        //
    }
    
    public function storeURL(StoreURLRequest $request): JsonResponse
    {
        try {
            return $this->response(
                    [
                        'data' => [
                            'shorten_url' => $this->urlServices->storeURL($request->url)
                        ],
                        'message' => 'URL stored and shorten successfully'
                    ]
                );
        } catch (\Exception $e) {
            return $this->error('Error shorten URL: ' . $e->getMessage());
        }
    }

    public function redirect($url)
    {
        
    }
}
