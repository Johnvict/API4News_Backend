<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MiscController extends Controller
{

    public function sortNewsIntoSingleArray($allnews, $category = null) {
        $news = array();
        for($j=0; $j< count($allnews); $j++){
            $type = $category == null? $this->whatTypeOfNews($j) :  $category;
            foreach($allnews[$j] as $new) {
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

    public function getCategoryTitle($type) {
        $category = null;
        switch($type){
            case 'across the World':
                $category = 'newsdatas';
                break;
            case 'business':
                $category = 'businesses';
                break;
            case 'entertainment';
                $category = 'entertainments';
                break;
            case 'health':
                $category = 'healths';
                break;
            case 'science':
                $category = 'sciences';
                break;
            case 'sports' | 'Sports':
                $category = 'sports';
                break;
            case 'Technology':
                $category = 'technologies';
            break;
        }
        return $category;
    }
}
