<?php

namespace App\Services;

use App\Models\RadioStation;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;

class RadioApiService
{
    const API_HITFM = 1;
    const API_RADIORELAX = 2;

    const API_HITFM_URL = 'https://hitfm.md/data/current.json';
    const API_RADIORELAX_URL = 'https://www.radiorelax.md/getsong.php';

    private GuzzleClient $client;

    public static array $apis = [
        self::API_HITFM => self::API_HITFM_URL,
        self::API_RADIORELAX => self::API_RADIORELAX_URL,
    ];
    public static array $apiLabels = [
        self::API_HITFM => 'HitFM',
        self::API_RADIORELAX => 'RadioRelax',
    ];

    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
    }


    public function getStationInfo(RadioStation $station){
        $api = $station->api_id;
        $url = ($api) ? self::$apis[$api] : null;
        $request = null;
        if($api == self::API_HITFM){
            $request = new Request(
                'GET',
                $url,
                ['Content-Type' => 'application/json']
            );

            $response = $this->client->send($request);
            $body = $response->getBody();
            $data = json_decode($body, true);
            if (isset($data[$station->source_meta]['artist']) && isset($data[$station->source_meta]['song']))
                return [
                    'status' => 'success',
                    'artist' => $data[$station->source_meta]['artist'],
                    'song' => $data[$station->source_meta]['song'],
                ];


        }
        elseif($api == self::API_RADIORELAX){
            $requestData = [
                'channel' => $station->source_meta
            ];
            $request = new Request(
                'POST',
                $url,
                ['Content-Type' => 'application/json'],
                json_encode($requestData)
            );
            $response = $this->client->send($request);
            $body = $response->getBody();
            $data = json_decode($body, true);
            if (isset($data['name'])) {
                $tmp = explode(' - ', $data['name']);
                return [
                    'status' => 'success',
                    'artist' => $tmp[1],
                    'song' => $tmp[0],
                ];
            }

        }

        return [
            'status' => 'error',
            'artist' => '&nbsp;',
            'song' => $station->description
        ];
    }

}
