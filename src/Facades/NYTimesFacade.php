<?php

namespace ShamiTheWebdeveloper\NYTimes\Facades;

use Illuminate\Support\Facades\Facade;

class NYTimes extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'nytimes';
    }
}
