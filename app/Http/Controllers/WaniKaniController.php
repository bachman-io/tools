<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class WaniKaniController extends Controller
{
    public function index() {
        if(Cache::tags('wanikani')->has('user')){
            $data = $this->getSummary();
            $data['title'] = 'WaniKani - Tools - Bachman I/O';
            //dd($data);
            return view('wanikani.index', $data);
        } else {
            $data['title'] = 'WaniKani - Tools - Bachman I/O';
            return view('updating', $data);
        }
    }

    public function levels($level = null)
    {
        $valid_levels = [
            '1-10',
            '11-20',
            '21-30',
            '31-40',
            '41-50',
            '51-60'
        ];
        if (is_null($level) || !in_array($level, $valid_levels)) {
            return redirect()->to('wanikani');
        }

        if(Cache::tags('wanikani')->has('user')){
            $data = $this->getLevels($level);
            $data['title'] = 'WaniKani Levels ' . $level . ' - Tools - Bachman I/O';
            $data['type_names'] = ['Radicals', 'Kanji', 'Vocabulary'];
            $data['level_range'] = $level;
            //dd($data);
            return view('wanikani.levels', $data);
        } else {
            $data['title'] = 'WaniKani Levels ' . $level . ' - Tools - Bachman I/O';
            return view('updating', $data);
        }
    }

    private function getSummary() {
        $result = [];
        $result['user'] = Cache::tags('wanikani')->get('user');
        $result['burned_items'] = Cache::tags('wanikani')->get('burned_items');
        $result['study_queue'] = Cache::tags('wanikani')->get('study_queue');
        $result['recent_unlocks'] = Cache::tags('wanikani')->get('recent_unlocks');
        $result['critical_items'] = Cache::tags('wanikani')->get('critical_items');
        $result['srs_distribution'] = Cache::tags('wanikani')->get('srs_distribution');
        return $result;
    }

    private function getLevels($level)
    {
        $result = [];
        $result['user'] = Cache::tags('wanikani')->get('user');
        $result['levels'] = Cache::tags('wanikani')->get('levels_' . $level);
        return $result;
    }
}
