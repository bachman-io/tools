<?php

namespace App\Services;

use App\Models\AniList\Anime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;


class AniList {
    private $apiClient;

    public function __construct()
    {
        $this->apiClient = new Client([
            'base_uri' => 'https://graphql.anilist.co'
        ]);
    }

    public function clearCache(Command $command)
    {
        $command->info('Clearing AniList Cache...');
        Cache::tags('anilist')->flush();
        $command->comment('Done!');
    }

    public function truncateTable(Command $command)
    {
        $command->info('Truncating Database Table...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('anime')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $command->comment('Done!');
    }

    public function getAnimeLists(Command $command)
    {
        $command->info('Fetching Anime Lists from AniList...');
        $query = <<<'EOD'
query ($id: Int) {
  MediaListCollection(userId: $id, type: ANIME) {
    lists {
      name
      entries {
        media {
          coverImage {
            medium
            large
          }
          title {
            native
            romaji
            english
          }
          description
          episodes
          duration
          format
          averageScore
          studios {
            edges {
              id
              isMain
              node {
                name
              }
            }
          }
          siteUrl
        }
        progress
        score
      }
    }
  }
}
EOD;
        $variables['id'] = 122750;
        $response = $this->apiClient->post('/', [
            'json' => [
                'query' => $query,
                'variables' => $variables
            ]
        ]);

        $data = json_decode($response->getBody(), true)['data'];

        $command->comment('Done!');
        $command->info('Storing Data in Database Table...');

        foreach ($data['MediaListCollection']['lists'] as $list) {
            $list_name = $list['name'];
            foreach ($list['entries'] as $entry) {
                $anime = new Anime;
                $anime->title_english = $entry['media']['title']['english'];
                $anime->title_romaji = $entry['media']['title']['romaji'];
                $anime->title_native = $entry['media']['title']['native'];
                $anime->description = $entry['media']['description'];
                $anime->status = $list_name;
                $anime->progress = $entry['progress'];
                $anime->episodes = $entry['media']['episodes'];
                $anime->format = $entry['media']['format'];
                $anime->duration = $entry['media']['duration'];
                $anime->average_score = $entry['media']['averageScore'] / 10;
                $anime->my_score = $entry['score'];
                $anime->site_url = $entry['media']['siteUrl'];
                $anime->cover_thumbnail = $entry['media']['coverImage']['medium'];
                $anime->cover_image = $entry['media']['coverImage']['large'];

                $studios = [];

                foreach ($entry['media']['studios']['edges'] as $studio) {
                    if ($studio['isMain']) {
                        $studios[] = $studio['node']['name'];
                    }
                }
                foreach ($entry['media']['studios']['edges'] as $studio) {
                    if (!$studio['isMain']) {
                        $studios[] = $studio['node']['name'];
                    }
                }

                $anime->studios = implode(', ', $studios);

                $anime->save();
            }
        }
        $command->comment('Done!');
    }

    public function cacheItems(Command $command)
    {
        $command->info('Adding Items to Cache...');
        Cache::tags('anilist')->put('currently_watching', $this->getCurrentlyWatching(), 120);
        Cache::tags('anilist')->put('watch_pool', $this->getWatchPool(), 120);
        Cache::tags('anilist')->put('top_ten_best', $this->getTopTenBest(), 120);
    }

    private function getCurrentlyWatching()
    {
        $currently_watching = Anime::where([
            ['status', '=', 'Watching'],
            ['progress', '>', '0']
        ])->get();

        return $currently_watching;
    }

    private function getWatchPool()
    {
        $watch_pool = Anime::where([
            ['status', '=', 'Watching'],
            ['progress', '=', '0']
        ])->get();

        return $watch_pool;
    }

    private function getTopTenBest()
    {
        $result = Anime::where([
            ['status', '=', 'Completed']
        ])->get();

        $sorted = $result->sortByDesc('score_factor');
        $top_teb_best = $sorted->take(10);

        return $top_teb_best;
    }
}