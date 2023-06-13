<?php

namespace App\Controllers;

use App\Services\CurrencyService;
use Helpers\Helper;
use System\Database\DatabaseInterface;

class CurrencyController
{
    /**
     * @return array
     */
    public function index(): array
    {
        $currencies = CurrencyService::get(['currency', 'code', 'bid', 'ask', 'effective_date']);
        $navigationLinks = Helper::getNavigationLinks();

        return [
            'path' => 'currency/index',
            'currencies' => $currencies,
            'links' => $navigationLinks
        ];
    }
}