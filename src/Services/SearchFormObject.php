<?php

namespace App\Services;

use App\DataObjects\SearchObject;

class SearchFormObject
{
    private static ?SearchObject $instance = null;

    private function __construct(){}

    private function __clone(){}

    public static function getInstance(): SearchObject {
        if(null === self::$instance) {
            self::$instance = new SearchObject();
        }
        return self::$instance;
    }
}
