<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StoreController extends Controller
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = 'https://api.salla.sa';
        $this->apiKey = env('SALLA_API_KEY');
    }

    public function updateVisitors($storeId)
    {
        $response = Http::withToken($this->apiKey)
                        ->get("{$this->baseUrl}/stores/{$storeId}/visitors");

        $visitors = $response->json();

        return response()->json($visitors);
    }
}