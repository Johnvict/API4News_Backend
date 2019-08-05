<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use function GuzzleHttp\json_decode;
use GuzzleHttp\Client;
use App\Exchangerate;

class CurrencyController extends Controller
{
    public function __construct()
    {
        $this->urlNairaToDollar = 'https://free.currconv.com/api/v7/convert?q=NGN_USD&compact=ultra&apiKey=fe802381043de3bad21c';
        // $this->urlNairaToEuro = 'https://free.currconv.com/api/v7/convert?q=NGN_EUR&compact=ultra&apiKey=fe802381043de3bad21c';
    }

    public function fetchToDollar() {
        $nairaToDollar = $this->getCurrency($this->urlNairaToDollar);
        return  $nairaToDollar['NGN_USD'];
    }

    // public function fetchToEuro() {
    //     $nairaToEuro = $this->getCurrency($this->urlNairaToEuro);
    //     return $nairaToEuro['NGN_EUR'];
    // }

    public function getCurrency($url) {
        $client = new Client();

        $response = $client->request('GET', $url);
        $currencyRate = json_decode((string) $response->getBody(), true); //['articles'];
        return  $currencyRate;  //this is now iterable
    }

    public function exchangeRate() {
        $dollar = $this->fetchToDollar();

        $check =  Exchangerate::all();
        if (count($check) > 0) {
            $rate = Exchangerate::find(1);
        } else {
            $rate = new Exchangerate();
        }

        $rate->usd = $dollar;
        $rate->sourceTime = time();

        if (count($check) > 0 ? $rate->update() : $rate->save()) {
            return $dollar;
        }
    }
}
