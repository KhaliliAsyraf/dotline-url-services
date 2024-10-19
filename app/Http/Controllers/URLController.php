<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetAnalyticDataRequest;
use App\Http\Requests\GetOriginalURLRequest;
use App\Http\Requests\StoreURLRequest;
use App\Interfaces\URLInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class URLController extends BaseController
{
    public function __construct(protected URLInterface $urlInterface)
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
            DB::beginTransaction(); // To deal with concurrent access and ensure data consistency
            
            $shorten_url = $this->urlInterface->storeURL($request->url);

            DB::commit();

            return $this->response(
                    [
                        'data' => [
                            'shorten_url' => $shorten_url
                        ],
                        'message' => 'URL stored and shorten successfully.'
                    ]
                );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
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
            DB::beginTransaction(); // To deal with concurrent access and ensure data consistency
            $url = $this->urlInterface->getOriginalURL($request->url, $request->ip);
            DB::commit();
            return redirect($url);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode() ? $e->getCode() : 500);
        }
    }
    
    /**
     * getAnalyticData
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function getAnalyticData(GetAnalyticDataRequest $request): JsonResponse
    {
        try {
            return $this->response(
                    [
                        'data' => $this->urlInterface->getAnalyticData($request?->url),
                        'message' => 'Success'
                    ]
                );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode() ? $e->getCode() : 500);
        }
    }
}
