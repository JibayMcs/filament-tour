<?php

namespace JibayMcs\FilamentTour\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JibayMcs\FilamentTour\FilamentTour
 */
class FilamentTour extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \JibayMcs\FilamentTour\FilamentTour::class;
    }
}
