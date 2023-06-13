<?php

namespace App\Controllers;

use App\Models\ConvertedCurrencyHistory;
use Helpers\Helper;

class ConvertedCurrencyHistoryController
{
    /**
     * @return array
     */
    public function index(): array
    {
        $history = ConvertedCurrencyHistory::get();
        $navigationLinks = Helper::getNavigationLinks();

        return [
            'path' => 'convertedCurrencyHistory/index',
            'history' => $history,
            'links' => $navigationLinks
        ];
    }
}