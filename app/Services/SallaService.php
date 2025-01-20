<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class SallaService
{
protected $baseUrl;
protected $apiKey;

public function __construct()
{
$this->baseUrl = 'https://api.salla.sa';
$this->apiKey = env('SALLA_API_KEY');
}

public function getStoreVisitors($storeId)
{
$response = Http::withToken($this->apiKey)
->get("{$this->baseUrl}/stores/{$storeId}/visitors");

return $response->json();
}
}