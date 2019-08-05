<?php

namespace App\Http\Controllers;


use App\Client;
use App\Country;
use App\Subscription;

use App\Sport;
use App\Health;
use App\Science;
use App\Business;
use App\Newsdata;
use App\Technology;
use App\Entertainment;

use App\Sourcehistory;
use App\ClientNewsReceived;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

use App\Http\Controllers\MiscController;
use App\Http\Controllers\NewsClientController;
use App\Http\Controllers\ShuffleNewsController;

class ClientController extends Controller
{

    public function __construct()
    {
        $this->miscCtrl = new MiscController();
        $this->newsClientCtrl = new NewsClientController();
        $this->shuffleNewsCtrl = new ShuffleNewsController();
    }

    public function clientAccessControl($client) {
        $today = new Carbon('today');
        $sub = Subscription::where([['client_id', '=', $client->id], ['expiry', '>', $today]])->first();

        if ($sub) {
            $countries = explode("|", $sub->countries);
            $categories = explode("|", $sub->categories);
            $clientCountries = array();
            foreach($countries as $country) {
                array_push($clientCountries, Country::find($country));
            }
            return (['countries' => $clientCountries, 'categories' => $categories]);
        } else {
            return null;
        }
    }

    public function getNewsApi() {
        $apiKey = Input::get('apiKey');
        $client = Client::whereApikey($apiKey)->first();

        if ($client) {
            $response = $this->getNews(new Request(), $client);
            return $response;
        }
        return response()->json(['error' => 'Unauthorized user', 'message' => 'Register at https://news.adeunique.com to get access'], 401);
    }

    public function getNews(Request $request, $apiCallerClient = null) {
        if ($apiCallerClient != null ) {
            $client = $apiCallerClient;
        } else {
            $client = Client::whereDomain($request->requestUrl)->first();
        }

        if ($client !== null) {
            $access = $this->clientAccessControl($client);
            if ($access == null) {
                return response()->json([
                    'inactive' => true,
                    // 'unauthorized' => true,
                    'mesage' => 'Your account is no longer active. You may have ran out of subscription'
                ]); //Unauthorized
            }
            $clientID = $client->id;

            $receivedToday = DB::table('client_news_receiveds')->orderBy('id')->where(['client_id' => $clientID])->get();
            $receivedBefore = DB::table('old_client_news_receiveds')->orderBy('id')->where(['client_id' => $clientID])->get();

            $pastDaysNews = false;
            if(count($receivedBefore) > 0){
                $pastDaysNews = $this->getForPrevious($receivedBefore);
            }

            if (count($receivedToday) > 0){
                $todayNews = $this->getExistedToday($receivedToday);

                $timeNow = time();
                $lastSourceTime = Sourcehistory::find(1);
                $diff = $timeNow - $lastSourceTime->sourceTime;

                if ($diff >= 600) {        //More than one hour
                    // ?SHUFFLE CLIENT NEWS AGAIN
                    $anHourAgo = new Carbon('-1 hour'); //Time as at one hour ago
                    $newHeadLines = $this->shuffleNewsCtrl->newsBasedOnRequest($access['categories'], $access['countries'], $anHourAgo);

                    // Arrange the newly shuffled news into today news
                    $todayNews = $this->addShuffledNews($todayNews, $newHeadLines);
                }
            } else {
                $todayNews = $this->getNewsBasedOnRequest($access['categories'], $access['countries']);
                $this->saveRequestRecords($todayNews, $clientID);
            }
            return response()->json(['success' => true, 'status' => 201, 'news' => $todayNews, 'old' => $pastDaysNews]);
        } else {
            return response()->json(['unauthorized' => true, 'message' => 'Unauthorized user'], 401);
        }

    }

    public function addShuffledNews($todayNews, $newHeadLines) {
        foreach($todayNews as $today) {
            foreach($newHeadLines as $headline) {
                if ($today['category'] == $headline['category']) {
                    array_push($today['articles'], $headline);
                }
            }
        }
        return $todayNews;
    }

    public function getNewsBasedOnRequest ($categories, $countries) {
        $allTodayNews = array();
        foreach ($categories as $category) {
            $categoryTitle = $category;
            foreach ($countries as $country) {
                $news = $this->selectedCatAndCount($category, $country->id);
            }
            array_push($allTodayNews, [
                'category' => $categoryTitle,
                'articles' => $news
                ]);
        }
        return $allTodayNews;
    }

    public function getNewForToday() {
    }

    public function getExistedToday($news) {
        $todayNews = $this->newsClientCtrl->getTodayNews($news);
        return $todayNews;
    }

    public function getForPrevious($news) {
        $pastDaysNews = $this->newsClientCtrl->getOldNews($news);
        return $pastDaysNews;
    }


    public function saveRequestRecords($news, $clientID) {
        foreach($news as $newsCategory) {
            foreach($newsCategory['articles'] as $new) {
                $newRecord = new ClientNewsReceived();
                $newRecord->client_id = $clientID;
                $newRecord->news_category = $newsCategory['category'];
                $newRecord->news_id = $new->id;

                $exist = ClientNewsReceived::where([
                    'client_id' => $newRecord->client_id,
                    'news_category' => $newRecord->news_category,
                    'news_id' => $newRecord->news_id
                    ])->first();

                if (!$exist){
                    $newRecord->save();
                } else {
                    continue;
                }
            }
        }
        return true;
    }

    public function selectedCatAndCount($category, $countryID) {
        $category = strtolower($category);
        $allnews = array();
        switch($category) {
            case 'all' :
                array_push($allnews, Newsdata::inRandomOrder()->whereCountryId($countryID)->take(5)->get() );
                break;
            case 'business':
                array_push($allnews, Business::inRandomOrder()->whereCountryId($countryID)->take(5)->get() );
                break;
            case 'entertainment' :
                array_push($allnews, Entertainment::inRandomOrder()->whereCountryId($countryID)->take(5)->get() );
                break;
            case 'health' :
                array_push($allnews, Health::inRandomOrder()->whereCountryId($countryID)->take(5)->get() );
                break;
            case 'science' :
                array_push($allnews, Science::inRandomOrder()->whereCountryId($countryID)->take(5)->get() );
                break;
            case 'sports' :
                array_push($allnews, Sport::inRandomOrder()->whereCountryId($countryID)->take(5)->get() );
                break;
            case 'technology' :
                array_push($allnews, Technology::inRandomOrder()->whereCountryId($countryID)->take(5)->get() );
            break;
        }

        $news = array();
        $news = $this->miscCtrl->sortNewsIntoSingleArray($allnews, $category);

        return $news;
    }

}
