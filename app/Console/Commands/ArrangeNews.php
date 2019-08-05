<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Old\Oldnewsdata;
use App\Old\Oldbusiness;
use App\Old\Oldentertainment;
use App\Old\Oldhealth;
use App\Old\Oldscience;
use App\Old\Oldsport;
use App\Old\Oldtechnology;
use App\OldClientNewsReceived;


class ArrangeNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:arrange';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will sort the news in our database at scheduled time to avoid old news in circulation';

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
        $this->info("Yeah, I'm doing it");
        $this->info("\n\n\t\rNews Sorting in PROGRESS");

        $this->sortAllNews();
        $this->arrangeRequestReceived();
        $this->deleteOverdue();

    }

    private function sortAllNews()
    {
        $now = Carbon::now()->subDays(1)->toDateTimeString();     //  24 hours ago
        // $today = Carbon::now()->hour(0)->minute(0)->second(0)->toDateTimeString();   // Since 12 midnight today
        // $now = Carbon::now()->subDays(1)->hour(1)->minute(0)->second(0)->toDateTimeString();     //Since 12 midnight yesterday
        // $this->info($now);
        $newses = DB::table('newsdatas')->orderBy('id')->where('created_at', '<=', $now)->get();
        // return $this->info($newses);
        if ($newses){
            foreach($newses as $news) {
                $this->info($news->id);
                $newsData = new Oldnewsdata();
                $newsData->old_id = $news->id;
                $newsData->country_id = $news->country_id;
                $newsData->source = $news->source;
                $newsData->author = $news->author;
                $newsData->title = $news->title;
                $newsData->url = $news->url;
                $newsData->urlToImage = $news->urlToImage;
                $newsData->publishedAt = $news->publishedAt;
                $newsData->description = $news->description;
                $newsData->content = $news->content;
                if ($newsData->save()) {
                    $this->info($news->id .': Deleted');
                    DB::table('newsdatas')->delete($news->id);
                }
            }
        }
        // DB::table('newsdatas')->orderBy('id')->where('created_at', '<=', $now)->chunk(5, function ($newses) {
        //     foreach ($newses as $news) {
        //         $this->info($news->id);
        //         $newsData = new Oldnewsdata();
        //         $newsData->old_id = $news->id;
        //         $newsData->country_id = $news->country_id;
        //         $newsData->source = $news->source;
        //         $newsData->author = $news->author;
        //         $newsData->title = $news->title;
        //         $newsData->url = $news->url;
        //         $newsData->urlToImage = $news->urlToImage;
        //         $newsData->publishedAt = $news->publishedAt;
        //         $newsData->description = $news->description;
        //         $newsData->content = $news->content;
        //         if ($newsData->save()) {
        //             DB::table('newsdatas')->delete($news->id);
        //         }
        //     }
        //     $this->info('Next chunk All News');
        // });

        $this->sortBusinessNews();
    }

    private function sortBusinessNews()
    {
        // $now =Carbon::now()->subDays(1)->toDateTimeString();
        $now =Carbon::now()->subDays(1)->hour(0)->minute(0)->second(0)->toDateTimeString();
        $newses = DB::table('businesses')->orderBy('id')->where('created_at', '<=', $now)->get();
        if ($newses){
            foreach($newses as $news) {
                $this->info($news->id);
                $newsData = new Oldbusiness();
                $newsData->old_id = $news->id;
                $newsData->country_id = $news->country_id;
                $newsData->source = $news->source;
                $newsData->author = $news->author;
                $newsData->title = $news->title;
                $newsData->url = $news->url;
                $newsData->urlToImage = $news->urlToImage;
                $newsData->publishedAt = $news->publishedAt;
                $newsData->description = $news->description;
                $newsData->content = $news->content;
                if ($newsData->save()) {
                    $this->info($news->id .': Deleted');
                    DB::table('businesses')->delete($news->id);
                }
            }
        }

        // DB::table('businesses')->orderBy('id')->where('created_at', '<=', $now)->chunk(5, function ($newses) {
        //     foreach ($newses as $news) {
        //         $this->info($news->id);
        //         $newsData = new Oldbusiness();
        //         $newsData->old_id = $news->id;
        //         $newsData->country_id = $news->country_id;
        //         $newsData->source = $news->source;
        //         $newsData->author = $news->author;
        //         $newsData->title = $news->title;
        //         $newsData->url = $news->url;
        //         $newsData->urlToImage = $news->urlToImage;
        //         $newsData->publishedAt = $news->publishedAt;
        //         $newsData->description = $news->description;
        //         $newsData->content = $news->content;
        //         if ($newsData->save()) {
        //             DB::table('businesses')->delete($news->id);
        //         }
        //     }
        //     $this->info('Next chunk Business news');
        // });

        $this->sortEntertainmentNews();
    }

    private function sortEntertainmentNews()
    {
        // $now =Carbon::now()->subDays(1)->toDateTimeString();
        $now =Carbon::now()->subDays(1)->hour(0)->minute(0)->second(0)->toDateTimeString();
        $newses = DB::table('entertainments')->orderBy('id')->where('created_at', '<=', $now)->get();
        if ($newses){
            foreach($newses as $news) {
                $this->info($news->id);
                $newsData = new Oldentertainment();
                $newsData->old_id = $news->id;
                $newsData->country_id = $news->country_id;
                $newsData->source = $news->source;
                $newsData->author = $news->author;
                $newsData->title = $news->title;
                $newsData->url = $news->url;
                $newsData->urlToImage = $news->urlToImage;
                $newsData->publishedAt = $news->publishedAt;
                $newsData->description = $news->description;
                $newsData->content = $news->content;
                if ($newsData->save()) {
                    $this->info($news->id .': Deleted');
                    DB::table('entertainments')->delete($news->id);
                }
            }
        }
        // DB::table('entertainments')->orderBy('id')->where('created_at', '<=', $now)->chunk(5, function ($newses) {
        //     foreach ($newses as $news) {
        //         $this->info($news->id);
        //         $newsData = new Oldentertainment();
        //         $newsData->old_id = $news->id;
        //         $newsData->country_id = $news->country_id;
        //         $newsData->source = $news->source;
        //         $newsData->author = $news->author;
        //         $newsData->title = $news->title;
        //         $newsData->url = $news->url;
        //         $newsData->urlToImage = $news->urlToImage;
        //         $newsData->publishedAt = $news->publishedAt;
        //         $newsData->description = $news->description;
        //         $newsData->content = $news->content;
        //         if ($newsData->save()) {
        //             $this->info($news->id .': Deleted');
        //             DB::table('entertainments')->delete($news->id);
        //         }
        //     }
        //     $this->info('Next chunk Entertainment news');
        // });

        $this->sortHealthNews();
    }

    private function sortHealthNews()
    {
        // $now =Carbon::now()->subDays(1)->toDateTimeString();
        $now =Carbon::now()->subDays(1)->hour(0)->minute(0)->second(0)->toDateTimeString();
        $newses = DB::table('healths')->orderBy('id')->where('created_at', '<=', $now)->get();
        if ($newses){
            foreach($newses as $news) {
                $this->info($news->id);
                $newsData = new Oldhealth();
                $newsData->old_id = $news->id;
                $newsData->country_id = $news->country_id;
                $newsData->source = $news->source;
                $newsData->author = $news->author;
                $newsData->title = $news->title;
                $newsData->url = $news->url;
                $newsData->urlToImage = $news->urlToImage;
                $newsData->publishedAt = $news->publishedAt;
                $newsData->description = $news->description;
                $newsData->content = $news->content;
                if ($newsData->save()) {
                    $this->info($news->id .': Deleted');
                    DB::table('healths')->delete($news->id);
                }
            }
        }
        // DB::table('healths')->orderBy('id')->where('created_at', '<=', $now)->chunk(5, function ($newses) {
        //     foreach ($newses as $news) {
        //         $this->info($news->id);
        //         $newsData = new Oldhealth();
        //         $newsData->old_id = $news->id;
        //         $newsData->country_id = $news->country_id;
        //         $newsData->source = $news->source;
        //         $newsData->author = $news->author;
        //         $newsData->title = $news->title;
        //         $newsData->url = $news->url;
        //         $newsData->urlToImage = $news->urlToImage;
        //         $newsData->publishedAt = $news->publishedAt;
        //         $newsData->description = $news->description;
        //         $newsData->content = $news->content;
        //         if ($newsData->save()) {
        //             DB::table('healths')->delete($news->id);
        //         }
        //     }
            // $this->info('Next chunk Health news');
        // });

        $this->sortScienceNews();
    }

    private function sortScienceNews()
    {
        // $now =Carbon::now()->subDays(1)->toDateTimeString();
        $now =Carbon::now()->subDays(1)->hour(0)->minute(0)->second(0)->toDateTimeString();
        $newses = DB::table('sciences')->orderBy('id')->where('created_at', '<=', $now)->get();
        if ($newses){
            foreach($newses as $news) {
                $this->info($news->id);
                $newsData = new Oldscience();
                $newsData->old_id = $news->id;
                $newsData->country_id = $news->country_id;
                $newsData->source = $news->source;
                $newsData->author = $news->author;
                $newsData->title = $news->title;
                $newsData->url = $news->url;
                $newsData->urlToImage = $news->urlToImage;
                $newsData->publishedAt = $news->publishedAt;
                $newsData->description = $news->description;
                $newsData->content = $news->content;
                if ($newsData->save()) {
                    $this->info($news->id .': Deleted');
                    DB::table('sciences')->delete($news->id);
                }
            }
        }
        // DB::table('sciences')->orderBy('id')->where('created_at', '<=', $now)->chunk(5, function ($newses) {
        //     foreach ($newses as $news) {
        //         $this->info($news->id);
        //         $newsData = new Oldscience();
        //         $newsData->old_id = $news->id;
        //         $newsData->country_id = $news->country_id;
        //         $newsData->source = $news->source;
        //         $newsData->author = $news->author;
        //         $newsData->title = $news->title;
        //         $newsData->url = $news->url;
        //         $newsData->urlToImage = $news->urlToImage;
        //         $newsData->publishedAt = $news->publishedAt;
        //         $newsData->description = $news->description;
        //         $newsData->content = $news->content;
        //         if ($newsData->save()) {
        //             DB::table('sciences')->delete($news->id);
        //         }
        //     }
        //     $this->info('Next chunk Science news');
        // });

        $this->sortSportsNews();
    }

    private function sortSportsNews()
    {
        // $now =Carbon::now()->subDays(1)->toDateTimeString();
        $now =Carbon::now()->subDays(1)->hour(0)->minute(0)->second(0)->toDateTimeString();
        $newses = DB::table('sports')->orderBy('id')->where('created_at', '<=', $now)->get();
        if ($newses){
            foreach($newses as $news) {
                $this->info($news->id);
                $newsData = new Oldsport();
                $newsData->old_id = $news->id;
                $newsData->country_id = $news->country_id;
                $newsData->source = $news->source;
                $newsData->author = $news->author;
                $newsData->title = $news->title;
                $newsData->url = $news->url;
                $newsData->urlToImage = $news->urlToImage;
                $newsData->publishedAt = $news->publishedAt;
                $newsData->description = $news->description;
                $newsData->content = $news->content;
                if ($newsData->save()) {
                    $this->info($news->id .': Deleted');
                    DB::table('sports')->delete($news->id);
                }
            }
        }
        // DB::table('sports')->orderBy('id')->where('created_at', '<=', $now)->chunk(5, function ($newses) {
        //     foreach ($newses as $news) {
        //         $this->info($news->id);
        //         $newsData = new Oldsport();
        //         $newsData->old_id = $news->id;
        //         $newsData->country_id = $news->country_id;
        //         $newsData->source = $news->source;
        //         $newsData->author = $news->author;
        //         $newsData->title = $news->title;
        //         $newsData->url = $news->url;
        //         $newsData->urlToImage = $news->urlToImage;
        //         $newsData->publishedAt = $news->publishedAt;
        //         $newsData->description = $news->description;
        //         $newsData->content = $news->content;
        //         if ($newsData->save()) {
        //             DB::table('sports')->delete($news->id);
        //         }
        //     }
        //     $this->info('Next chunk Sports News');
        // });

        $this->sortTechnologyNews();
    }
    private function sortTechnologyNews()
    {
        // $now = Carbon::now()->subDays(1)->toDateTimeString();
        $now =Carbon::now()->subDays(1)->hour(0)->minute(0)->second(0)->toDateTimeString();
        $newses = DB::table('technologies')->orderBy('id')->where('created_at', '<=', $now)->get();
        if ($newses){
            foreach($newses as $news) {
                $this->info($news->id);
                $newsData = new Oldtechnology();
                $newsData->old_id = $news->id;
                $newsData->country_id = $news->country_id;
                $newsData->source = $news->source;
                $newsData->author = $news->author;
                $newsData->title = $news->title;
                $newsData->url = $news->url;
                $newsData->urlToImage = $news->urlToImage;
                $newsData->publishedAt = $news->publishedAt;
                $newsData->description = $news->description;
                $newsData->content = $news->content;
                if ($newsData->save()) {
                    $this->info($news->id .': Deleted');
                    DB::table('technologies')->delete($news->id);
                }
            }
        }
        // DB::table('technologies')->orderBy('id')->where('created_at', '<=', $now)->chunk(5, function ($newses) {
        //     foreach ($newses as $news) {
        //         $this->info($news->id);
        //         $newsData = new Oldtechnology();
        //         $newsData->old_id = $news->id;
        //         $newsData->country_id = $news->country_id;
        //         $newsData->source = $news->source;
        //         $newsData->author = $news->author;
        //         $newsData->title = $news->title;
        //         $newsData->url = $news->url;
        //         $newsData->urlToImage = $news->urlToImage;
        //         $newsData->publishedAt = $news->publishedAt;
        //         $newsData->description = $news->description;
        //         $newsData->content = $news->content;
        //         if ($newsData->save()) {
        //             $this->info($news->id .': Deleted');
        //             DB::table('technologies')->delete($news->id);
        //         }
        //     }
        //     $this->info('Next chunk Technology News');
        // });

        $this->info('All news sorted COMPLETELY');
        $this->info('Sorting users Request Now');

        $this->arrangeRequestReceived();

    }

    public function arrangeRequestReceived() {
        $now =Carbon::now()->subDays(0)->hour(0)->minute(0)->second(0)->toDateTimeString();
        $midnightToday = Carbon::now()->hour(0)->minute(0)->second(0)->toDateTimeString();
        // return $this->info($now);
        // return $this->info($midnightToday);
        // $time = DB::table('client_news_receiveds')->find(1)->created_at;
        // if ($time > $midnightToday) {
        //     return $this->info('Graeter time    '.'created At: '.$time. '     MidnightToday: '.$midnightToday);
        // } else {
        //     return $this->info('MidnightToday: '.$midnightToday. '   created At: '.$time);
        // }
        $requests = DB::table('client_news_receiveds')->orderBy('id')->where('created_at', '<=', $midnightToday)->get();

        // return $this->info($requests);
        foreach($requests as $request) {
            $newData =  new OldClientNewsReceived();
            $newData->client_id = $request->client_id;
            $newData->news_category = $request->news_category;
            $newData->news_id = $request->news_id;
            $this->info($request->id .': User Request Deleted');
            if ($newData->save()) {
                $this->info($request->id .': User Request Deleted');
                DB::table('client_news_receiveds')->delete($request->id);
            }
        }

        $this->deleteOverdue();
        $this->info('All Users Request sorted competely');
    }
    public  function deleteOverdue()
    {
        $twoWeeksAgo = new Carbon('-2 weeks');
        // $allnews = Oldnewsdata::inRandomOrder()->where([['created_at', '>=', $twoWeeksAgo]])->get();
        // $allnews = Oldbusiness::inRandomOrder()->where([['created_at', '>=', $twoWeeksAgo]])->get();
        // $allnews = Oldentertainment::inRandomOrder()->where([['created_at', '>=', $twoWeeksAgo]])->get();
        // $allnews = Oldhealth::inRandomOrder()->where([['created_at', '>=', $twoWeeksAgo]])->get();
        // $allnews = Oldscience::inRandomOrder()->where([['created_at', '>=', $twoWeeksAgo]])->get();
        // $allnews = Oldsport::inRandomOrder()->where([['created_at', '>=', $twoWeeksAgo]])->get();
        // $overDueNews = OldClientNewsReceived::inRandomOrder()->where([['created_at', '<=', $twoWeeksAgo]])->take(3)->pluck('id');
        $overDueNews = OldClientNewsReceived::inRandomOrder()->where([['created_at', '<=', $twoWeeksAgo]])->pluck('id');
        $this->info('Deleting Client request info that have lasted beyond '. $twoWeeksAgo);
        $this->info(count($overDueNews).' items will be deleted');

        $this->info($overDueNews);
        OldClientNewsReceived::destroy($overDueNews);

    }

}
