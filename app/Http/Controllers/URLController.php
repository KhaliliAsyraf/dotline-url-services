<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetOriginalURLRequest;
use App\Http\Requests\StoreURLRequest;
use App\Interfaces\URLInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class URLController extends BaseController
{
    public function __construct(protected URLInterface $urlServices)
    {
        //
    }
        
    /**
     * Store URL
     *
     * @param  Request $request
     * @return JsonResponse
     */
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
        
    /**
     * Redirect to original URL
     *
     * @param  Request $url
     * @return RedirectResponse
     */
    public function redirect(GetOriginalURLRequest $request): RedirectResponse|JsonResponse
    {
        try {
            return redirect($this->urlServices->getOriginalURL($request->url));
        } catch (\Exception $e) {
            return $this->error('Error shorten URL: ' . $e->getMessage());
        }
    }
}
