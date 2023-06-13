<?php

namespace App\Services;

use App\Models\Currency;
use DateTime;

class CurrencyService
{
    /**
     * Get currencies from the nearest available date using the api
     *
     * @return array
     */
    public static function getFromApi(): array
    {
        $date = new DateTime();

        do {
            $availableDate = $date->format('Y-m-d');
            $url = "http://api.nbp.pl/api/exchangerates/tables/c/{$availableDate}/?format=json";

            $headers = get_headers($url);
            $responseCode = substr($headers[0], 9, 3);

            $date->modify('-1 day');
        } while ($responseCode === '404');

        $availableCurrencies = json_decode(file_get_contents($url), true);

        return reset($availableCurrencies);
    }

    /**
     * Get currencies with the nearest available date from the database
     *
     * @param array $fields
     * @return array
     */
    public static function get(array $fields = ['*']): array
    {
        $date = (new DateTime())->format('Y-m-d');
        $currencies = Currency::getByEffectiveDate($date, $fields);

        if ($currencies) {
            return $currencies;
        }

        $apiCurrencies = static::getFromApi();
        $currencies = Currency::getByEffectiveDate($apiCurrencies['effectiveDate'], $fields);

        if (!$currencies) {
            foreach ($apiCurrencies['rates'] as $rate) {
                $rate['effective_date'] = $apiCurrencies['effectiveDate'];
                Currency::create($rate);
            }

            $currencies = Currency::getByEffectiveDate($apiCurrencies['effectiveDate'], $fields);
        }

        return $currencies;
    }
}