<?php

class CurrencyConverter {
    /**
     * Generate a random exchange rate between 0 and 2
     * @param  string           $currency               The given currency
     * @return float                                    The random exchange rate
     */
    private static function getExchangeRate($currency) {
        $rate = mt_rand(0, 200) / 100;
        return $rate;
    }

    /**
     * Convert in EUR a given amount
     * @param  string           $amount                     The currency to convert
     * @param  string           $currency                   The preferred currency
     * @return string                                       The converted amount
     */
    protected static function convert($amount, $currency = null) {
        $currencies = ["EUR" => "€", "USD" => "$", "GBP" => "£"];

        $am = preg_match('/([^\d])([\d\.\,]+)/u', $amount, $matched);
        $currency = (!is_null($currency) ? $currencies[$currency] : $matched[1]);
        $amount = (float)$matched[2];

        $rate = self::getExchangeRate($amount);
        $converted = ($rate === 0) ? 1 : (($rate < 1) ? ($amount/$rate) : ($amount*$rate));
        return round($converted, 2) . " " . $currency;
    }
}
