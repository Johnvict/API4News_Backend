<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Country;
use App\Newsdata;
use App\Business;
use App\Entertainment;
use App\Health;
use App\Science;
use App\Sport;
use App\Technology;
use Illuminate\Support\Facades\Input;

class NewsAsJsonController extends Controller
{
   
    public function asSpecialRequest() {
        $country = Input::get('country');
         $category = Input::get('category');
        $apiKey = Input::get('apiKey');
        
        $isUserValid = Client::whereApikey($apiKey)->first();

        if ($isUserValid) {
            $request = new Request(['country' => $country, 'category' => $category]);
            
            $response = $this->getNews($request, $isUserValid);

            return $response;
        }
        return 'Invalid Api key';
    }

    public function getNews(Request $request) {
        $countryN = Country::whereName($request->country)->first();
        $countryID = $countryN?  $countryN->id : null;
        // return $countryID

        if ($request->category == 'all' && $request->country == 'all') {
            //ALL CATEGORY ACROSS ALL COUNTRIES
            $news = $this->allCategoriesAllCountry();
        } else if ($request->category == 'all' && $request->country != 'all') {
            // ALL CATEGORY IN COUNTRY ct
            $news = $this->allCategoriesButCountry($countryID);
        } else if ($request->category != 'all' && $request->country == 'all') {
            // CATEGORY cat ACROSS ALL COUNTRIES
            $news = $this->allCountriesButCategory($request->category);
        } else if ($request->category  != 'all' && $request->country != 'all') {
            // CATEGORY cat in COUNTRY ct ALONE
            $news = $this->selectedCatAndCount($request->category, $countryID);
        }

        return response()->json(['success' => true, 'status' => 'ok', 'news' => $news, 'oldNews' => false]);
    }

    public function allCategoriesButCountry($countryID) {
        $allnews =array();

        array_push($allnews, Newsdata::inRandomOrder()->whereCountryId($countryID)->get() );
        array_push($allnews, Business::inRandomOrder()->whereCountryId($countryID)->get() );
        array_push($allnews, Entertainment::inRandomOrder()->whereCountryId($countryID)->get() );
        array_push($allnews, Health::inRandomOrder()->whereCountryId($countryID)->get() );
        array_push($allnews, Science::inRandomOrder()->whereCountryId($countryID)->get() );
        array_push($allnews, Sport::inRandomOrder()->whereCountryId($countryID)->get() );
        array_push($allnews, Technology::inRandomOrder()->whereCountryId($countryID)->get() );

        $news = array();
        $news = $this->sortNewsIntoSingleArray($allnews);
        return $news;
    }

    public function allCountriesButCategory($category) {
        $category = strtolower($category);
        $allnews = array();

        switch($category) {
            case 'all' :
                array_push($allnews, Newsdata::inRandomOrder()->get() );
                break;
            case 'business':
                array_push($allnews, Business::inRandomOrder()->get() );
                break;
            case 'entertainment' :
                array_push($allnews, Entertainment::inRandomOrder()->get() );
                break;
            case 'health' :
                array_push($allnews, Health::inRandomOrder()->get() );
                break;
            case 'science' :
                array_push($allnews, Science::inRandomOrder()->get() );
                break;
            case 'sports' :
                array_push($allnews, Sport::inRandomOrder()->get() );
                break;
            case 'technology' :
                array_push($allnews, Technology::inRandomOrder()->get() );
            break;
        }

        $news = array();
        $news = $this->sortNewsIntoSingleArray($allnews, $category);

        return $news;

    }

    public function selectedCatAndCount($category, $countryID) {
        $category = strtolower($category);
        $allnews = array();
        switch($category) {
            case 'all' :
                array_push($allnews, Newsdata::inRandomOrder()->whereCountryId($countryID)->get() );
                break;
            case 'business':
                array_push($allnews, Business::inRandomOrder()->whereCountryId($countryID)->get() );
                break;
            case 'entertainment' :
                array_push($allnews, Entertainment::inRandomOrder()->whereCountryId($countryID)->get() );
                break;
            case 'health' :
                array_push($allnews, Health::inRandomOrder()->whereCountryId($countryID)->get() );
                break;
            case 'science' :
                array_push($allnews, Science::inRandomOrder()->whereCountryId($countryID)->get() );
                break;
            case 'sports' :
                array_push($allnews, Sport::inRandomOrder()->whereCountryId($countryID)->get() );
                break;
            case 'technology' :
                array_push($allnews, Technology::inRandomOrder()->whereCountryId($countryID)->get() );
            break;
        }

        $news = array();
        $news = $this->sortNewsIntoSingleArray($allnews, $category);

        return $news;

    }
    
    // RETURNS  5 NEWS FROM EACH OF THE CATEGORIES REGARDLESS OF CONTRY
    public function allCategoriesAllCountry() {
        $allnews = array();
        // $data = Newsdata::where('created_at', '<=', Carbon::now()->subDays(5)->toDateTimeString ())->get(); //Older than today
        // $data = Newsdata::where('created_at', '>=', Carbon::now()->subDays(2)->toDateTimeString ())->get(); //Younger than today
        // return $data;
    
        array_push($allnews, Newsdata::inRandomOrder()->get() );
        array_push($allnews, Business::inRandomOrder()->get() );
        array_push($allnews, Entertainment::inRandomOrder()->get() );
        array_push($allnews, Health::inRandomOrder()->get() );
        array_push($allnews, Science::inRandomOrder()->get() );
        array_push($allnews, Sport::inRandomOrder()->get() );
        array_push($allnews, Technology::inRandomOrder()->get() );

        $news = array();
        $news = $this->sortNewsIntoSingleArray($allnews);
        return $news;
    }

    public function sortNewsIntoSingleArray($allnews, $category = null) {
        $news = array();
        for($j=0; $j< count($allnews); $j++){
            $type = $category == null? $this->whatTypeOfNews($j) :  $category;
            foreach($allnews[$j] as $new) {
                $new->type = $type;
                if($new->content == "null"){
                    $new->content = substr($new->content, 0, random_int(250, 255)).'...';
                    $new->title = 'I got null';
                } else {
                }
                if ($new->author== "null") {
                    $new->author = null;                        //PUT NULL TO WHEREVER THE AUTHOR IS NULL
                }
                if (!($new->content == "null")) {
                    array_push($news, $new);                // DO  NOT INCLUDE IF NEWS CONTENT IS NULL
                }
            }
        }
        
        return $news;
    }
    
    public function whatTypeOfNews($j) {
        $type = null;
        switch($j) {
            case 0:
                $type = 'Across the World';
                break;
            case 1:
                $type = 'Business';
                break;
            case 2:
                $type = 'Entertainment';
                break;
            case 3:
                $type = 'Health';
                break;
            case 4:
                $type = 'Science';
                break;
            case 5:
                $type = 'Sports';
                break;
            case 6:
                $type = 'Technology';
                break;
        }
        return $type;
    }
}
