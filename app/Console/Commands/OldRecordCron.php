<?php

namespace App\Console\Commands;

use App\Models\TableFive;
use App\Models\TableSix;
use App\Models\TableThirtyTwo;
use Illuminate\Console\Command;

class OldRecordCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oldrecord:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean table daily';

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
     * @return int
     */
    public function handle()
    {
        \Log::info("Cron is working fine");
        TableFive::all()->delete();
        TableSix::all()->delete();
        TableThirtyTwo::all()->delete();
    }
}
