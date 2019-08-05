<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\NewsfetcherController;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ObtainNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:obtain';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command makes our app goes online to feth news';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
         $this->newsfetcher  = new NewsfetcherController;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $res = $this->newsfetcher->obtainAllNews();
        $this->info($res);

        // $time = Carbon::now();
        // Storage::append('/public/cronJobs/News_obtain_results.txt', 'News was fetched at: '.$time);
    }

}
