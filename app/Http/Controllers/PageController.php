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
        return redirect()->to('wanikani');
    }
}
