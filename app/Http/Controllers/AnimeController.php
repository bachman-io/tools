<?php

namespace App\Http\Controllers;

use App\Models\AniList\Anime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnimeController extends Controller
{
    public function index()
    {
        $data['title'] = 'Anime - Tools - Bachman I/O';
        if (Cache::tags('anilist')->has('top_ten_best')) {
            $data['currently_watching'] = Cache::tags('anilist')->get('currently_watching');
            $data['watch_pool'] = Cache::tags('anilist')->get('watch_pool');
            $data['top_ten_best'] = Cache::tags('anilist')->get('top_ten_best');
            return view('anime.index', $data);
        } else {
            return view('updating', $data);
        }
    }

    public function commitToWatch()
    {
        $data['title'] = 'Anime: Commit to Watch - Tools - Bachman I/O';
        if (Cache::tags('anilist')->has('top_ten_best')) {
            $planning = Anime::where('status', 'Planning')->get();
            if ($planning->isNotEmpty()) {
                $data['anime'] = $planning->random();
            } else {
                $data['anime'] = false;
            }
            return view('anime.commit_to_watch', $data);
        } else {
            return view('updating', $data);
        }
    }
}
