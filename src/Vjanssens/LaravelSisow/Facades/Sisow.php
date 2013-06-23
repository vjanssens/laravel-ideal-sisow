<?php

namespace Vjanssens\LaravelSisow\Facades;

use Illuminate\Support\Facades\Facade;

class Sisow extends Facade
{
	/**
    * Get the registered name of the component.
    *
    * @return string
    */
    protected static function getFacadeAccessor()
    {
        return 'sisow';
    }
}