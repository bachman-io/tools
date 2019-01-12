<?php

namespace App\Services;

use App\Models\WaniKani\Assignment;
use App\Models\WaniKani\SrsStage;
use App\Models\WaniKani\Subject;
use App\Models\WaniKani\Summary;
use App\Models\WaniKani\User;
use App\Models\WaniKani\ReviewStatistic;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Carbon\Carbon;

class WaniKani
{
    private $apiClient;
    private $cdnClient;

    public function __construct()
    {
        $this->apiClient = new Client([
            'base_uri' => 'https://api.wanikani.com/v2/',
            'headers' => [
                'Authorization' => 'Bearer f91898e8-20b8-4c2c-834d-663d169449ba'
            ]
        ]);
        $this->cdnClient = new Client();
    }

    public function truncateTables(Command $command) {
        $command->info('Truncating Database Tables...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $command->comment('Truncating Review Statistics...');
        DB::table('review_statistics')->truncate();
        $command->comment('Truncating Summaries...');
        DB::table('summaries')->truncate();
        $command->comment('Truncating Assignments...');
        DB::table('assignments')->truncate();
        if ($command->option('force') || (Carbon::now()->hour === 8)) {
            $command->comment('Truncating Subjects...');
            DB::table('subjects')->truncate();
            $command->comment('Truncating SRS Stages...');
            DB::table('srs_stages')->truncate();
            $command->comment('Truncating Users...');
            DB::table('users')->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $command->comment('Done!');
    }

    public function updateUser(Command $command)
    {
        if ($command->option('force') || (Carbon::now()->hour === 8)) {
            $command->info('Updating User...');
            $response = $this->apiClient->get('user');
            $u = json_decode($response->getBody(), true)['data'];

            $user = new User;
            $user->id = $u['id'];
            $user->username = $u['username'];
            $user->level = $u['level'];
            $user->max_level_granted_by_subscription = $u['max_level_granted_by_subscription'];
            $user->profile_url = $u['profile_url'];
            $user->started_at = Carbon::createFromFormat(
                \DateTime::ISO8601,
                substr(
                    $u['started_at'],
                    0,
                    strpos($u['started_at'], ".")
                ) . '+0000');
            $user->subscribed = $u['subscribed'];
            $user->current_vacation_started_at = is_null($u['current_vacation_started_at'])
                ? null
                : Carbon::createFromFormat(
                    \DateTime::ISO8601,
                    substr(
                        $u['current_vacation_started_at'],
                        0,
                        strpos($u['current_vacation_started_at'], ".")
                    ) . '+0000');

            $user->save();
            $command->comment('Done!');
        }
    }

    public function updateSrsStages(Command $command)
    {
        if ($command->option('force') || (Carbon::now()->hour === 8)) {
            $command->info('Updating SRS Stages...');
            $response = $this->apiClient->get('srs_stages');
            $srs_stages = json_decode($response->getBody(), true)['data'];

            foreach($srs_stages as $srs_stage) {
                $srs = new SrsStage;
                $srs->srs_stage = $srs_stage['srs_stage'];
                $srs->srs_stage_name = $srs_stage['srs_stage_name'];
                $srs->interval = $srs_stage['interval'];
                $srs->accelerated_interval = $srs_stage['accelerated_interval'];

                $srs->save();
            }
            $command->comment('Done!');
        }
}

    public function updateSubjects(Command $command)
    {
        if ($command->option('force') || (Carbon::now()->hour === 8)) {
            $command->info('Updating Subjects...');
            $next_page_url = 'https://api.wanikani.com/v2/subjects?hidden=false';
            $response = $this->apiClient->get($next_page_url);
            $total = json_decode($response->getBody(), true)['total_count'];
            $bar = $command->getOutput()->createProgressBar($total);
            $bar->start();

            while(!is_null($next_page_url)) {
                $response = $this->apiClient->get($next_page_url);
                $next_page_url = json_decode($response->getBody(), true)['pages']['next_url'];
                $subjects = json_decode($response->getBody(), true)['data'];

                foreach ($subjects as $s) {
                    $subject = new Subject;

                    $subject->id = $s['id'];
                    $subject->object = $s['object'];
                    $subject->data_updated_at = Carbon::createFromFormat(
                        \DateTime::ISO8601,
                        substr(
                            $s['data_updated_at'],
                            0,
                            strpos($s['data_updated_at'], ".")
                        ) . '+0000');

                    $subject->level = $s['data']['level'];

                    $subject->characters = $s['data']['characters'];

                    //TODO: Follow up with WaniKani on missing Coral radical SVG, it's throwing a 403
                    if ($s['object'] === 'radical' && is_null($s['data']['characters'] && $s['id'] !== 8794)) {
                        foreach($s['data']['character_images'] as $character_image) {
                            if($character_image['content_type'] === 'image/svg+xml'
                                && $character_image['metadata']['inline_styles'] == true) {
                                try {
                                    $svg = $this->cdnClient->get($character_image['url']);
                                    $subject->character_image = (string)$svg->getBody();
                                } catch(\Exception $exception) {
                                    $subject->character_image = '?';
                                }
                            }
                        }
                    } else {
                        $subject->character_image = null;
                    }

                    $temp_meanings = [];

                    foreach ($s['data']['meanings'] as $meaning) {
                        if($meaning['accepted_answer'] === true) {
                            $temp_meanings[] = $meaning['meaning'];
                        }
                    }

                    $subject->meanings = implode(', ', $temp_meanings);

                    $subject->document_url = $s['data']['document_url'];

                    if($s['object'] === 'radical' || $s['object'] === 'kanji') {
                        $subject->amalgamation_subject_ids = implode(', ', $s['data']['amalgamation_subject_ids']);
                    }

                    if($s['object'] === 'vocabulary' || $s['object'] === 'kanji') {
                        $subject->component_subject_ids = implode(', ', $s['data']['component_subject_ids']);
                    }

                    if($s['object'] === 'kanji') {
                        $on_yomi = [];
                        $kun_yomi = [];
                        $nanori = [];

                        foreach($s['data']['readings'] as $reading) {
                            switch($reading['type']) {
                                case 'onyomi':
                                    $on_yomi[] = $reading['reading'];
                                    break;
                                case 'kunyomi':
                                    $kun_yomi[] = $reading['reading'];
                                    break;
                                case 'nanori':
                                    $nanori[] = $reading['reading'];
                                    break;
                            }
                        }

                        $subject->on_yomi = !empty($on_yomi) ? implode(', ', $on_yomi) : null;
                        $subject->kun_yomi = !empty($kun_yomi) ? implode(', ', $kun_yomi) : null;
                        $subject->nanori = !empty($nanori) ? implode(', ', $nanori) : null;
                    } else {
                        $subject->on_yomi = null;
                        $subject->kun_yomi = null;
                        $subject->nanori = null;
                    }

                    if($s['object'] === 'vocabulary') {
                        $readings = [];
                        foreach($s['data']['readings'] as $reading) {
                            if($reading['accepted_answer'] === true) {
                                $readings[] = $reading['reading'];
                            }
                        }
                        $subject->kana = implode(', ', $readings);
                        $subject->parts_of_speech = implode(', ', $s['data']['parts_of_speech']);
                    } else {
                        $subject->kana = null;
                        $subject->parts_of_speech = null;
                    }

                    $subject->save();
                    $bar->advance();
                }
            }
            $bar->finish();
            echo PHP_EOL;
        }
    }

    public function updateAssignments(Command $command)
    {
        $command->info('Updating Assignments...');
        $next_page_url = 'https://api.wanikani.com/v2/assignments?unlocked=true&hidden=false';
        $response = $this->apiClient->get($next_page_url);
        $total = json_decode($response->getBody(), true)['total_count'];
        $bar = $command->getOutput()->createProgressBar($total);
        $bar->start();

        while(!is_null($next_page_url)) {
            $response = $this->apiClient->get($next_page_url);
            $next_page_url = json_decode($response->getBody(), true)['pages']['next_url'];
            $assignments = json_decode($response->getBody(), true)['data'];

            foreach ($assignments as $a) {
                $assignment = new Assignment;

                $assignment->id = $a['id'];
                $assignment->data_updated_at = Carbon::createFromFormat(
                    \DateTime::ISO8601,
                    substr(
                        $a['data_updated_at'],
                        0,
                        strpos($a['data_updated_at'], ".")
                    ) . '+0000');

                $assignment->subject_id = $a['data']['subject_id'];
                $assignment->srs_stage = $a['data']['srs_stage'];
                $assignment->unlocked_at = is_null($a['data']['unlocked_at'])
                    ? null
                    : Carbon::createFromFormat(
                        \DateTime::ISO8601,
                        substr(
                            $a['data']['unlocked_at'],
                            0,
                            strpos($a['data']['unlocked_at'], ".")
                        ) . '+0000');
                $assignment->started_at = is_null($a['data']['started_at'])
                    ? null
                    : Carbon::createFromFormat(
                        \DateTime::ISO8601,
                        substr(
                            $a['data']['started_at'],
                            0,
                            strpos($a['data']['started_at'], ".")
                        ) . '+0000');
                $assignment->passed_at = is_null($a['data']['passed_at'])
                    ? null
                    : Carbon::createFromFormat(
                        \DateTime::ISO8601,
                        substr(
                            $a['data']['passed_at'],
                            0,
                            strpos($a['data']['passed_at'], ".")
                        ) . '+0000');
                $assignment->burned_at = is_null($a['data']['burned_at'])
                    ? null
                    : Carbon::createFromFormat(
                        \DateTime::ISO8601,
                        substr(
                            $a['data']['burned_at'],
                            0,
                            strpos($a['data']['burned_at'], ".")
                        ) . '+0000');
                $assignment->available_at = is_null($a['data']['available_at'])
                    ? null
                    : Carbon::createFromFormat(
                        \DateTime::ISO8601,
                        substr(
                            $a['data']['available_at'],
                            0,
                            strpos($a['data']['available_at'], ".")
                        ) . '+0000');
                $assignment->resurrected_at = is_null($a['data']['resurrected_at'])
                    ? null
                    : Carbon::createFromFormat(
                        \DateTime::ISO8601,
                        substr(
                            $a['data']['resurrected_at'],
                            0,
                            strpos($a['data']['resurrected_at'], ".")
                        ) . '+0000');

                $assignment->passed = $a['data']['passed'];
                $assignment->resurrected = $a['data']['resurrected'];

                $assignment->save();
                $bar->advance();
            }
        }
        $bar->finish();
        echo PHP_EOL;
    }

    public function updateSummaries(Command $command)
    {
        $command->info('Updating Summaries...');

        $response = $this->apiClient->get('summary');
        $sy = json_decode($response->getBody(), true)['data'];

        $summary = new Summary;

        $summary->type = 'lessons';
        $summary->hours_from_now = 0;
        $summary->available_at = Carbon::createFromFormat(
            \DateTime::ISO8601,
            substr(
                $sy['lessons'][0]['available_at'],
                0,
                strpos($sy['lessons'][0]['available_at'], ".")
            ) . '+0000');

        $summary->subject_ids = empty($sy['lessons'][0]['subject_ids'])
            ? null
            : implode (', ', $sy['lessons'][0]['subject_ids']);

        $summary->save();

        $hours = 0;

        foreach($sy['reviews'] as $review) {
            $summary = new Summary;

            $summary->type = 'reviews';
            $summary->hours_from_now = $hours;
            $summary->available_at = Carbon::createFromFormat(
                \DateTime::ISO8601,
                substr(
                    $review['available_at'],
                    0,
                    strpos($review['available_at'], ".")
                ) . '+0000');

            $summary->subject_ids = empty($review['subject_ids'])
                ? null
                : implode (', ', $review['subject_ids']);

            $summary->save();
            $hours++;
        }
        $command->comment('Done!');
    }

    public function updateReviewStatistics(Command $command)
    {
        $command->info('Updating Review Statistics...');
        $next_page_url
            = 'https://api.wanikani.com/v2/review_statistics?hidden=false&updated_after=2019-01-05T00:00:00.000000Z';
        $response = $this->apiClient->get($next_page_url);
        $total = json_decode($response->getBody(), true)['total_count'];
        $bar = $command->getOutput()->createProgressBar($total);
        $bar->start();

        while(!is_null($next_page_url)) {
            $response = $this->apiClient->get($next_page_url);
            $next_page_url = json_decode($response->getBody(), true)['pages']['next_url'];
            $review_statistics = json_decode($response->getBody(), true)['data'];

            foreach ($review_statistics as $r) {
                $review_statistic = new ReviewStatistic;

                $review_statistic->id = $r['id'];
                $review_statistic->data_updated_at = Carbon::createFromFormat(
                    \DateTime::ISO8601,
                    substr(
                        $r['data_updated_at'],
                        0,
                        strpos($r['data_updated_at'], ".")
                    ) . '+0000');

                $review_statistic->subject_id = $r['data']['subject_id'];
                $review_statistic->meaning_correct = $r['data']['meaning_correct'];
                $review_statistic->meaning_incorrect = $r['data']['meaning_incorrect'];
                $review_statistic->meaning_max_streak = $r['data']['meaning_max_streak'];
                $review_statistic->meaning_current_streak = $r['data']['meaning_current_streak'];

                $review_statistic->reading_correct = $r['data']['reading_correct'];
                $review_statistic->reading_incorrect = $r['data']['reading_incorrect'];
                $review_statistic->reading_max_streak = $r['data']['reading_max_streak'];
                $review_statistic->reading_current_streak = $r['data']['reading_current_streak'];

                $review_statistic->percentage_correct = $r['data']['percentage_correct'];

                $review_statistic->save();
                $bar->advance();
            }
        }
        $bar->finish();
        echo PHP_EOL;
    }
}