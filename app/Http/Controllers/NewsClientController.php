<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewsClientController extends Controller
{

    public function getOldNews($news) {
        // $oldReceived = DB::table('old_client_news_receiveds')->orderBy('id')->where(['client_id' => $clientID])->get();

        $newses = array();
        foreach($news as $news) {
            $tableName = $this->newsTableName($news->news_category);
            if($newsData = DB::table('old'.$tableName)->whereOldId($news->news_id)->first()) {
                $newsData->news_category  = $news->news_category;
                array_push($newses, $newsData);
            };
        }
        $sortedOldNews = $this->sortThis($newses);
        // $sortedOldNews = $this->sortNews($newses);

        return $sortedOldNews;
    }

    public function getTodayNews($News) {

        $newses = array();
        foreach($News as $news) {
            $tableName = $this->newsTableName($news->news_category);
            // dd($tableName);
            if($newsData = DB::table($tableName)->find($news->news_id)) {
            // if($newsData = DB::table($news->news_category)->find($news->news_id)) {
                $newsData->news_category  = $news->news_category;
                array_push($newses, $newsData);
            };
        }

        // dd($newses);

        $sortedTodayNews = $this->sortThis($newses);
        // dd($sortedTodayNews);
        // $sortedTodayNews = $this->sortNews($newses);

        return $sortedTodayNews;
    }

    public function formatThis($new, $type) {
        return  [
                    'type' => $type,
                    'source' => $new->source,
                    'author' => $new->author,
                    'title' => $new->title,
                    'url' => $new->url,
                    'urlToImage' => $new->urlToImage,
                    'publishedAt' => $new->publishedAt,
                    'description' => $new->description,
                    'content' => $new->content,
        ];
    }

    public function sortThis($news) {

    $acrossTheWord = array('category' => 'Across The Word', 'articles' => array());
    $entertainment = array('category' => 'entertainment', 'articles' => array());
    $business = array('category' => 'business', 'articles' => array());
    $health = array('category' => 'health', 'articles' => array());
    $science = array('category' => 'science', 'articles' => array());
    $sports = array('category' => 'sports', 'articles' => array());
    $technology = array('category' => 'technology', 'articles' => array());

        foreach ($news as $new) {
            switch ($new->news_category) {
                case 'newsdatas':
                case 'oldnewsdatas':
                    array_push($acrossTheWord['articles'], $new);
                    break;
                case 'business':
                    array_push($business['articles'], $new);
                    break;
                case 'entertainment':
                    array_push($entertainment['articles'], $new);
                    break;
                case 'health':
                    array_push($health['articles'], $new);
                    break;
                case 'science':
                    array_push($science['articles'], $new);
                    break;
                case 'sports':
                    array_push($sports['articles'], $new);
                    break;
                case 'technology':
                    array_push($technology['articles'], $new);
                    break;
            }
        }

        $allNews = array();
        if (count($acrossTheWord['articles']) > 0) {
            array_push($allNews, $acrossTheWord);
        }
        if (count($business['articles']) > 0) {
            array_push($allNews, $business);
        }
        if (count($entertainment['articles']) > 0) {
            array_push($allNews, $entertainment);
        }
        if (count($health['articles']) > 0) {
            array_push($allNews, $health);
        }
        if (count($science['articles']) > 0) {
            array_push($allNews, $science);
        }
        if (count($sports['articles']) > 0) {
            array_push($allNews, $sports);
        }
        if (count($technology['articles']) > 0) {
            array_push($allNews, $technology);
        }

        return $allNews;
    }

    public function newsTableName($category) {
        $table = null;
        // dd($category);
        switch($category) {
            case 'Across The World':
                $table = 'newsdatas';
                break;
            case 'business':
                $table = 'businesses';
                break;
            case 'entertainment':
                $table = 'entertainments';
                break;
            case 'health':
                $table = 'healths';
                break;
            case 'science':
                $table = 'sciences';
                break;
            case 'sports':
                $table = 'sports';
                break;
            case 'technology':
                $table = 'technologies';
                break;
            }
        return $table;
    }
}
