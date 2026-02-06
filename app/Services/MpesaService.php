<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MpesaService
{
    public function getAccessToken()
    {
        $consumerKey = env('MPESA_CONSUMER_KEY');
        $consumerSecret = env('MPESA_CONSUMER_SECRET');
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $response = Http::withBasicAuth($consumerKey, $consumerSecret)->get($url);
        
        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        throw new \Exception("Failed to generate M-Pesa Access Token. Check your keys!");
    }

    public function initiateStkPush($phone, $amount, $orderReference)
    {
        $token = $this->getAccessToken();
        $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        
        $shortcode = env('MPESA_SHORTCODE');
        $passkey = env('MPESA_PASSKEY');
        $timestamp = date('YmdHis');
        $password = base64_encode($shortcode . $passkey . $timestamp);
        
        // This is the Ngrok link you just added to .env
        $callbackUrl = env('MPESA_CALLBACK_URL');

        $response = Http::withToken($token)->post($url, [
            "BusinessShortCode" => $shortcode,
            "Password" => $password,
            "Timestamp" => $timestamp,
            "TransactionType" => "CustomerPayBillOnline",
            "Amount" => (int) $amount,
            "PartyA" => $phone, 
            "PartyB" => $shortcode, 
            "PhoneNumber" => $phone,
            "CallBackURL" => $callbackUrl, 
            "AccountReference" => "Orbita Shop",
            "TransactionDesc" => "Payment for Order $orderReference"
        ]);

        return $response->json();
    }
}