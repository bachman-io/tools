<?php

namespace App\Console\Commands\WaniKani;

use App\Services\WaniKani;
use Illuminate\Console\Command;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wanikani:update {--f|force : Force an update of static content (normally once a week)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates database with info from WaniKani API V2';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->wk = new WaniKani;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Begin Update of WaniKani Resources');
        $this->comment('---');
        $wk = new WaniKani;
        $wk->truncateTables($this);
        $wk->updateUser($this);
        $wk->updateSrsStages($this);
        $wk->updateSubjects($this);
        $wk->updateAssignments($this);
        $wk->updateReviewStatistics($this);
        $wk->updateSummaries($this);
        $this->comment('---');
        $this->info('All Done!');
    }
}
