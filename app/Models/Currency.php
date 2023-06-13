<?php

namespace App\Models;

use App\Models\Model;
use Helpers\Helper;

class Currency extends Model
{
    protected static string $table = "currencies";
    protected static array $fillable = ['currency', 'code', 'bid', 'ask', 'effective_date'];

    /**
     * @param string $effective_date
     * @param array $fields
     * @return array|null
     */
    public static function getByEffectiveDate(string $effective_date, array $fields = ['*']): ?array
    {
        $query = sprintf(
            'SELECT %s FROM %s where effective_date = :effective_date',
            Helper::getSeparatedArrayByComma($fields),
            static::$table
        );
        $currencies = static::$db->query($query, ['effective_date' => $effective_date])->fetchAll();

        return $currencies === false ? null : $currencies;
    }
}