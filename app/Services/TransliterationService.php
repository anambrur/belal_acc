<?php

namespace App\Services;

use GuzzleHttp\Client;

class TransliterationService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        
    }

    public function transliterate($text)
    {
       
        $response = $this->client->request('GET', 'https://inputtools.google.com/request?text=' . urlencode($text) . '&itc=bn-t-i0-und&num=1&cp=0&cs=1&ie=utf-8&oe=utf-8&app=demopage', [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        $body = json_decode($response->getBody()->getContents(), true);
        return $body[1][0][1][0];
    }
}
