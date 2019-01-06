<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Carbon;
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

        $response = $client->get('study-queue');
        $study_queue = json_decode($response->getBody(), true)['requested_information'];

        $review_date_unix = $study_queue['next_review_date'];

        $review_date = Carbon::createFromTimestamp($review_date_unix, "America/New_York");

        if ($review_date->dayOfWeekIso <= 5) {
            if ($review_date->hour < 6) {
                $review_date->hour = 6;
            }
            if ($review_date->hour > 6) {
                if ($review_date->hour < 12) {
                    $review_date->hour = 12;
                }
                if ($review_date->hour > 12 && $review_date->hour < 18) {
                    $review_date->hour = 18;
                }
            }
        } else {
            if ($review_date->hour < 9) {
                $review_date->hour = 9;
            }
        }

        $result['review_date'] = $review_date;
        $result['study_queue'] = $study_queue;

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
