<?php

namespace App\Services;

use App\Currency;

class CurrencyService
{
    /**
     * Get all currencies
     * 
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getCurrencies()
    {
        $currencies = Currency::all();

        return $currencies;
    }
} 