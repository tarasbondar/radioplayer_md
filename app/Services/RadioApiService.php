<?php

namespace App\Services;

use App\Models\RadioStation;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;

class RadioApiService
{
    const API_HITFM = 1;
    const API_RADIORELAX = 2;
    const API_ROKS = 3;
    const API_ICECAST = 4;

    const API_HITFM_URL = 'https://hitfm.md/data/current.json';
    const API_RADIORELAX_URL = 'https://www.radiorelax.md/getsong.php';
    const API_ROKS_URL = 'http://217.26.165.218:47108/roks/broad.txt';

    private GuzzleClient $client;

    public static array $apis = [
        self::API_HITFM => self::API_HITFM_URL,
        self::API_RADIORELAX => self::API_RADIORELAX_URL,
        self::API_ROKS => self::API_ROKS_URL,
    ];
    public static array $apiLabels = [
        self::API_HITFM => 'HitFM',
        self::API_RADIORELAX => 'RadioRelax',
        self::API_ROKS => 'Roks',
        self::API_ICECAST => 'IceCast',
    ];

    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
    }


    public function getStationInfo(RadioStation $station){
        $request = null;
        $api = $station->api_id;
        $url = ($api && array_key_exists($api, self::$apis)) ? self::$apis[$api] : $station->source;

        $artist = '&nbsp;';
        $song = $station->description;
        $status = 'error';

        $cache_key = sprintf('radio_info_v1_%s', sha1(serialize([$api, $url, $station->source_meta])));
        $cacheDuration = 5;

        if ($data = cache($cache_key)) {
            cache([$cache_key => $data], now()->addSeconds($cacheDuration));

            return $data;
        }


        if($api == self::API_HITFM){
            $request = new Request(
                'GET',
                $url,
                ['Content-Type' => 'application/json']
            );

            $response = $this->client->send($request);
            $body = $response->getBody();
            $data = json_decode($body, true);
            if (isset($data[$station->source_meta]['artist']) && isset($data[$station->source_meta]['song'])){
                $status = 'success';
                $artist = $data[$station->source_meta]['artist'];
                $song = $data[$station->source_meta]['song'];
            }
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
                $status = 'success';
                $artist = $tmp[1];
                $song = $tmp[0];
            }

        }
        elseif($api == self::API_ROKS){
            $request = new Request(
                'GET',
                $url,
                ['Content-Type' => 'application/json']
            );

            $response = $this->client->send($request);
            $body = $response->getBody();
            $contents = $body->getContents();

            $lines = explode("\n", $contents);
            $string = $lines[0];
            $firstZeroIndex = strpos($string, "00");

            if ($firstZeroIndex !== false) {
                $secondZeroIndex = strpos($string, "00", $firstZeroIndex + 1);
                if ($secondZeroIndex !== false) {
                    $artistSong = trim(substr($string, $secondZeroIndex + 2));

                    $lastDashIndex = strrpos($artistSong, "-");

                    if ($lastDashIndex !== false) {
                        $status = 'succes';
                        $artist = trim(substr($artistSong, 0, $lastDashIndex));
                        $song = trim(substr($artistSong, $lastDashIndex + 1));

                    }
                }
            }

        }
        elseif($api == self::API_ICECAST){
            $artistSong = $this->getStreamMetadata($url);
            $lastDashIndex = strrpos($artistSong, "-");

            if ($lastDashIndex !== false) {
                $status = 'succes';
                $artist = trim(substr($artistSong, 0, $lastDashIndex));
                $song = trim(substr($artistSong, $lastDashIndex + 1));

            }
        }
        $result = [
            'status' => $status,
            'artist' => $artist,
            'song' => $song
        ];

        cache([$cache_key => $result], now()->addSeconds($cacheDuration));
        return $result;
    }


    public function getStreamMetadata($streamUrl): ?string
    {
        $needle = 'StreamTitle=';
        $ua = 'Dailymate Radio/1.0';

        $opts = ['http' => ['method' => 'GET',
            'header' => 'Icy-MetaData: 1',
            'user_agent' => $ua]
        ];

        $context = stream_context_create($opts);

        $icyMetaIntFound = false;
        $icyInterval = -1;
        $offset = 0;

        if(($headers = get_headers($streamUrl, 0, $context))) {
            foreach($headers as $h) {
                if(!(strpos(strtolower($h), 'icy-metaint:') === false)) {
                    $icyMetaIntFound = true;

                    $icyInterval = explode(':', $h)[1];

                    break;
                }
            }
        }

        if(!$icyMetaIntFound) {
            return null;
        }

        if($stream = fopen($streamUrl, 'r', false, $context)) {
            while($buffer = stream_get_contents($stream, $icyInterval, $offset)) {
                if(strpos($buffer, $needle) !== false) {
                    fclose($stream);

                    $title = explode($needle, $buffer)[1];
                    return substr($title, 1, strpos($title, ';') - 2);
                }

                $offset += $icyInterval;
            }
        }
        return null;
    }

}
