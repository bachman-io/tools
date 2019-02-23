<?php

namespace App\Console\Commands\AniList;

use App\Services\AniList;
use Illuminate\Console\Command;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'anilist:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Begin Update of AniList Data');
        $this->comment('---');
        $al = new AniList();
        $al->truncateTable($this);
        $al->getAnimeLists($this);
        $al->clearCache($this);
        $al->cacheItems($this);
        $this->comment('---');
        $this->info('All Done!');
    }
}
