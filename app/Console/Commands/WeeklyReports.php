<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ReportsController;

class WeeklyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weeklyreports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly report at weekend to team members and team leads';

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
        $reportControl = new ReportsController;
        $reportControl->weeklyHoursReport();
    }
}
