<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    public function index() {
        $data['title'] = 'Tools - Bachman I/O';
        return view('home', $data);
    }

    public function kanji() {
        if (Cache::has('wanikani')) {
            $data = Cache::get('wanikani');
        } else {
            $data = $this->getWaniKani();
        }
        $data['title'] = 'Kanji - Tools - Bachman I/O';
        //dd($data);
        return view('kanji', $data);
    }

    private function getWaniKani() {
        $client = new Client([
            'base_uri' => 'https://www.wanikani.com/api/user/7893c431213382e421ee2d62487fd38a/'
        ]);

        $response = $client->get('level-progression');
        $jsonString = json_decode($response->getBody(), true);
        $result['user'] = $jsonString['user_information'];
        $result['level_progression'] = $jsonString['requested_information'];

        $response = $client->get('srs-distribution');
        $result['srs_distribution'] = json_decode($response->getBody(), true)['requested_information'];

        $response = $client->get('recent-unlocks/5');
        $result['recent_unlocks'] = json_decode($response->getBody(), true)['requested_information'];

        $response = $client->get('critical-items/95');
        $jsonString = json_decode($response->getBody(), true)['requested_information'];
        $result['critical_items'] = $jsonString;

        $response = $client->get('radicals');
        $radicals = json_decode($response->getBody(), true)['requested_information'];

        $levels = [];

        if (!empty($radicals)) {
            foreach ($radicals as $r) {
                $levels[$r['level']]['radicals'][] = $r;
            }
        }

        $response = $client->get('kanji');
        $kanji = json_decode($response->getBody(), true)['requested_information'];

        if (!empty($kanji)) {
            foreach ($kanji as $k) {
                $levels[$k['level']]['kanji'][] = $k;
            }
        }

        $response = $client->get('vocabulary');
        $vocabulary = json_decode($response->getBody(), true)['requested_information']['general'];
        if (!empty($vocabulary)) {
            foreach ($vocabulary as $v) {
                $levels[$v['level']]['vocabulary'][] = $v;
            }
        }

        $result['levels'] = $levels;

        Cache::set('wanikani', $result, 60);

        return $result;
    }
}
