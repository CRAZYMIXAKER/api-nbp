<?php

namespace App\Models;

use App\Models\Model;

class ConvertedCurrencyHistory extends Model
{
    protected static string $table = "converted_currencies_history";
    protected static array $fillable = ['requested_value', 'converted_value', 'currency_from_id', 'currency_to_id'];
}