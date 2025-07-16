<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

public function fetchOrders()
{
    $apiKey = env('SHIPPINGEASY_API_KEY');
    $apiSecret = env('SHIPPINGEASY_API_SECRET');
    $apiUrl = 'https://app.shippingeasy.com/api/orders';
    $timestamp = now()->toIso8601String(); // e.g., 2025-06-24T04:30:14Z

    $queryString = "api_key=$apiKey&timestamp=" . urlencode($timestamp);
    $stringToSign = "GET /api/orders $queryString";

    $signature = hash_hmac('sha256', $stringToSign, $apiSecret);

    $fullUrl = "$apiUrl?$queryString&signature=$signature";

    $response = Http::acceptJson()->get($fullUrl);

    if ($response->successful()) {
        return response()->json($response->json());
    }

    return response()->json([
        'error' => 'Failed to fetch orders',
        'status_code' => $response->status(),
        'details' => $response->body()
    ], $response->status());
}
