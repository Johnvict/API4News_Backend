<?php

namespace App\Http\Controllers;

use App\Sport;
use App\Health;
use App\Country;
use App\Science;
use App\Business;
use App\Newsdata;
use Carbon\Carbon;
use App\Technology;
use App\Entertainment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ShuffleNewsController extends Controller
{
    public function __construct()
    {
        $this->anHourAgo =  null;
    }

    public function newsBasedOnRequest ($categories, $countries, $anHourAgo) {
        $this->anHourAgo = $anHourAgo;
        $allTodayNews = array();
        // $categories = explode(",", $categories);
        foreach ($categories as $category) {
            $categoryTitle = $category;
            foreach ($countries as $country) {
                $news = $this->selectedCatAndCountS($category, $country->id);
            }
            array_push($allTodayNews, [
                'category' => $categoryTitle,
                'articles' => $news
            ]);
        }
        return $allTodayNews;
    }


    // RETURNS  5 NEWS FROM EACH OF THE CATEGORIES REGARDLESS OF CONTRY
    public function selectedCatAndCountS($category, $countryID)
    {
        $category = strtolower($category);
        $allnews = array();
        switch ($category) {
            case 'all':
                array_push($allnews, Newsdata::inRandomOrder()->where([['country_id', '=', $countryID], ['created_at', '>=', $this->anHourAgo]])->take(5)->get());
                break;
            case 'business':
                array_push($allnews, Business::inRandomOrder()->where([['country_id', '=', $countryID], ['created_at', '>=', $this->anHourAgo]])->take(5)->get());
                break;
            case 'entertainment':
                array_push($allnews, Entertainment::inRandomOrder()->where([['country_id', '=', $countryID], ['created_at', '>=', $this->anHourAgo]])->take(5)->get());
                break;
            case 'health':
                array_push($allnews, Health::inRandomOrder()->where([['country_id', '=', $countryID], ['created_at', '>=', $this->anHourAgo]])->take(5)->get());
                break;
            case 'science':
                array_push($allnews, Science::inRandomOrder()->where([['country_id', '=', $countryID], ['created_at', '>=', $this->anHourAgo]])->take(5)->get());
                break;
            case 'sports':
                array_push($allnews, Sport::inRandomOrder()->where([['country_id', '=', $countryID], ['created_at', '>=', $this->anHourAgo]])->take(5)->get());
                break;
            case 'technology':
                array_push($allnews, Technology::inRandomOrder()->where([['country_id', '=', $countryID], ['created_at', '>=', $this->anHourAgo]])->take(5)->get());
                break;
        }

        $news = array();
        $news = $this->sortNewsIntoSingleArray($allnews, $category);

        return $news;
    }

    public function sortNewsIntoSingleArray($allnews, $category = null)
    {
        $news = array();
        for ($j = 0; $j < count($allnews); $j++) {
            $type = $category == null ? $this->whatTypeOfNews($j) : $category;
            foreach ($allnews[$j] as $new) {
                $new->type = $type;
                if ($new->author == "null") {
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
                $type = 'across the World';
                break;
            case 1:
                $type = 'business';
                break;
            case 2:
                $type = 'entertainment';
                break;
            case 3:
                $type = 'health';
                break;
            case 4:
                $type = 'science';
                break;
            case 5:
                $type = 'sports';
                break;
            case 6:
                $type = 'technology';
                break;
        }
        return $type;
    }
}
