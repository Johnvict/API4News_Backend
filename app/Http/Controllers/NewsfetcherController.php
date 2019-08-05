<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Newsdata;
use App\Country;
use App\Business;
use App\Entertainment;
use App\Health;
use App\Science;
use App\Sport;
use App\Technology;

use function GuzzleHttp\json_decode;
use GuzzleHttp\Client;
use App\Sourcehistory;

// use GuzzleHttp\Exception\GuzzleException;


class NewsfetcherController extends Controller
{

    public function obtainAllNews() {
        // return 'Yeah, I got it from here @ NewsFetcher controller';
        // $countries = array('ng', 'se', 'gb', 'za', 'it','us');
        $countries = array( 'de', 'it', 'nl', 'ng', 'za', 'se', 'ch', 'ue', 'gb', 'us');
        // dd($countries);
        // return 'This is it: ' . $countries[0];
        for($j=0; $j< count($countries); $j++){
            for ($i= 0; $i<=6; $i++) {
                switch($i) {
                    case 0:
                        $news = $this->fetchNews($countries[$j], null);
                        $this->saveNews($news, 'All Categories', $countries[$j]);
                        break;
                    case 1:
                        $news = $this->fetchNews($countries[$j], 'business');
                        $this->saveNews($news, 'business', $countries[$j]);
                        break;
                    case 2:
                        $news = $this->fetchNews($countries[$j], 'entertainment');
                        $this->saveNews($news, 'entertainment', $countries[$j]);
                        break;
                    case 3:
                        $news = $this->fetchNews($countries[$j], 'health');
                        $this->saveNews($news, 'health', $countries[$j]);
                        break;
                    case 4:
                        $news = $this->fetchNews($countries[$j], 'science');
                        $this->saveNews($news, 'science', $countries[$j]);
                        break;
                    case 5:
                        $news = $this->fetchNews($countries[$j], 'sports');
                        $this->saveNews($news, 'sports', $countries[$j]);
                        break;
                    case 6:
                        $news = $this->fetchNews($countries[$j], 'technology');
                        $this->saveNews($news, 'technology', $countries[$j]);
                        break;
                }
            }
        }

        $updateSourceTime = Sourcehistory::find(1);
        $updateSourceTime->sourceTime = time();
        $updateSourceTime->update();
        return 'News Fetch Completed';
    }

    public function fetchNews( $country, $category=null) {
        $apiKey = '1cff61ce3cea4929b397389cf251dc79';
        $client = new Client();

        // https://newsapi.org/v2/top-headlines?country=ng&category=entertainment&apiKey=1cff61ce3cea4929b397389cf251dc79

        if ($category != null) {
            $url = 'https://newsapi.org/v2/top-headlines?country='.$country.'&category='.$category.'&apiKey='.$apiKey;
        } else {
            $url = 'https://newsapi.org/v2/top-headlines?country='.$country.'&apiKey='.$apiKey;
        }

        $response = $client->request('GET', $url);
        $newsItems = json_decode((string) $response->getBody(), true)['articles'];
        return  $newsItems;  //this is now iterable
    }
    // public function saveNews(Request $request, $category){
    public function saveNews($news, $category, $country){
        $country = Country::whereCode($country)->first();
        foreach ($news as $new) {
            switch($category) {
                case 'All Categories':
                    $newNews = new Newsdata();
                    $this->setNewsData($newNews, $new, $country);
                    $confirm = Newsdata::where(['title' => $newNews->title, 'url' => $newNews->url])->first();
                    break;
                case 'business':
                    $newNews = new Business();
                    $this->setNewsData($newNews, $new, $country);
                    $confirm = Business::where(['title' => $newNews->title, 'url' => $newNews->url])->first();
                    break;
                case 'entertainment':
                    $newNews = new Entertainment();
                    $this->setNewsData($newNews, $new, $country);
                    $confirm = Entertainment::where(['title' => $newNews->title, 'url' => $newNews->url])->first();
                    break;
                case 'health':
                    $newNews = new Health();
                    $this->setNewsData($newNews, $new, $country);
                    $confirm = Health::where(['title' => $newNews->title, 'url' => $newNews->url])->first();
                    break;
                case 'science':
                    $newNews = new Science();
                    $this->setNewsData($newNews, $new, $country);
                    $confirm = Science::where(['title' => $newNews->title, 'url' => $newNews->url])->first();
                    break;
                case 'sports':
                    $newNews = new Sport();
                    $this->setNewsData($newNews, $new, $country);
                    $confirm = Sport::where(['title' => $newNews->title, 'url' => $newNews->url])->first();
                    break;
                case 'technology':
                    $newNews = new Technology();
                    $this->setNewsData($newNews, $new, $country);
                    $confirm = Technology::where(['title' => $newNews->title, 'url' => $newNews->url])->first();
                    break;
                default :
                    $newNews = new Newsdata();
                    $this->setNewsData($newNews, $new, $country);
                    $confirm = Newsdata::where(['title' => $newNews->title, 'url' => $newNews->url])->first();
            }

            if (!$confirm) {
                $newNews->save();
            }  else {
                continue;
            }
        }

        return true;
    }

    public function setNewsData($newNews, $new, $country) {

        $author = 'null';
        if ($new['author'] !='') {
            $author = strlen($new['author']) > 100 ? substr($new['author'], 0, 50) : $new['author'];
        }

        $newNews->country_id = $country ? $country->id : null;
        $newNews->source = $new['source']['name'] == "" ? 'null' : $new['source']['name'];
        $newNews->author = $author;
        $newNews->title = $new['title'] =="" ? 'null' : $new['title'];
        $newNews->url = $new['url'] =="" ? 'null' : $new['url'];
        $newNews->urlToImage = $new['urlToImage'] =="" ? 'null' : $new['urlToImage'];;
        $newNews->publishedAt = $new['publishedAt'] == "" ? 'null' : $new['publishedAt'];
        $newNews->description = $new['description'] == ""? 'null' : $new['description'];
        $newNews->content = $new['content'] == "" ? 'null' : $new['content'];

        return $newNews;
    }

    public function loadAllNews() {
        $allNews = Newsdata::all();

        return response()->json(['success' => true, 'news' => $allNews]);
    }

    public function getNews($country, $category) {
        $countryN = Country::whereName($country)->first();
        $categoryN = Category::whereName($category)->first();

        $countryID = $countryN?  $countryN->id : null;
        $categoryID = $categoryN? $categoryN->id : null;
        if ($countryID !== null && $categoryID !== null ) {
            $news = Newsdata::where(['country_id' => $countryID, 'category_id' => $categoryID])->get();
            return response()->json(['success' => true, 'status' => 200, 'news' => $news]);
        }

        return response()->json(['success' => true, 'status' => 404, 'news' => []]);
    }
}
