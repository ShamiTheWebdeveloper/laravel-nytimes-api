<?php

namespace ShamiTheWebdeveloper\NYTimes\Facades;

use Illuminate\Support\Facades\Facade;

class NYTimesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'nytimes';
    }
}
