<?php

namespace App\Services;

use App\Models\ConvertedCurrencyHistory;
use App\Models\Currency;
use Helpers\Validations\ConverterValidation;

class ConvertService
{
    /**
     * @param array $fields
     * @return array
     */
    public static function checkConvertForm(array $fields): array
    {
        $errors = ConverterValidation::validate($fields);

        $amountInSourceCurrency = $fields['requested_value'];
        $sourceCurrency = $fields['currency_from'];
        $targetCurrency = $fields['currency_to'];
        $result = ['requested_value' => $amountInSourceCurrency];

        if ($errors) {
            return array_merge($result, ['errors' => $errors]);
        }

        $amountInTargetCurrency = static::convert($amountInSourceCurrency, $sourceCurrency, $targetCurrency);

        ConvertedCurrencyHistory::create([
            'requested_value' => $amountInSourceCurrency,
            'converted_value' => $amountInTargetCurrency,
            'currency_from_id' => $sourceCurrency,
            'currency_to_id' => $targetCurrency
        ]);

        return array_merge($result, ['converted_value' => $amountInTargetCurrency]);
    }

    /**
     * @param float $amount
     * @param int $sourceCurrency
     * @param int $targetCurrency
     * @return float
     */
    public static function convert(float $amount, int $sourceCurrency, int $targetCurrency): float
    {
        $sourceCurrencyRate = Currency::find($sourceCurrency, ['bid'])['bid'];
        $targetCurrencyRate = Currency::find($targetCurrency, ['bid'])['bid'];

        return round($sourceCurrencyRate / $targetCurrencyRate * $amount, 4);
    }
}