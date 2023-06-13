<?php

namespace App\Controllers;

use App\Services\ConvertService;
use App\Services\CurrencyService;
use Helpers\Helper;
use Helpers\Request;

class ConverterController
{
    /**
     * @return array
     */
    public function index(): array
    {
        $navigationLinks = Helper::getNavigationLinks();
        $currencies = CurrencyService::get(['id', 'currency', 'code']);

        return [
            'path' => 'converter/index',
            'currencies' => $currencies,
            'selects' => [
                'source' => ['name' => 'currency_from'],
                'target' => ['name' => 'currency_to'],
            ],
            'links' => $navigationLinks
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function convert(Request $request): array
    {
        $navigationLinks = Helper::getNavigationLinks();
        $currencies = CurrencyService::get(['id', 'currency', 'code']);

        $fields = $request->get('post');
        $provenСonversion = ConvertService::checkConvertForm($fields);

        $result = [
            'path' => 'converter/index',
            'currencies' => $currencies,
            'links' => $navigationLinks,
            'selects' => [
                'source' => [
                    'name' => 'currency_from',
                    'selected_id' => $fields['currency_from']
                ],
                'target' => [
                    'name' => 'currency_to',
                    'selected_id' => $fields['currency_to']
                ],
            ]
        ];

        return array_merge($result, $provenСonversion);
    }
}