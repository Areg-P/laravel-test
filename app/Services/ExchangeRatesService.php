<?php

namespace App\Services;

use App\Http\Resources\Rates;
use Illuminate\Support\Facades\Cache;

class ExchangeRatesService {
    private $ExchangeRates = [];
    protected $url = "https://developers.paysera.com";

    public function getExchangeRates() {
        $this->ExchangeRates = collect(
            Cache::has('rates') ? json_decode(Cache::get('rates')) :  
            $this->getJsonRates($this->url . "/tasks/api/currency-exchange-rates")
        );
        
        if(!Cache::has('rates')) {
            Cache::put('rates', json_encode($this->ExchangeRates), env('CACHE_DEATH_TIME'));
        }

        return Rates::make($this->ExchangeRates)->resolve();
    }

    public function Exchange($money, string $to="EUR") {
        if(empty($this->ExchangeRates) || !Cache::has('rates')) {
            $this->ExchangeRates = $this->getExchangeRates()['rates'];
        }

        $ExchangeTo = $this->ExchangeRates->$to;
        
        if($ExchangeTo && is_numeric($ExchangeTo)) {
            $exchanged = $money * $ExchangeTo;
            dd($money, $ExchangeTo);
            return $exchanged;
        } else return "Rate is not available for ". $ExchangeTo;
    }

    private function getJsonRates($url) {
        $httpClient = new \GuzzleHttp\Client();
        $request = $httpClient->get($url);

        return json_decode($request->getBody()->getContents());
    }
}