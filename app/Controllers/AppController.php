<?php

namespace App\Controllers;

use Helpers\Helper;

class AppController
{
    /**
     * @return array
     */
    public function index(): array
    {
        $navigationLinks = Helper::getNavigationLinks();

        return [
            'path' => 'layout/main',
            'links' => $navigationLinks
        ];
    }
}