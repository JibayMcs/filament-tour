<?php

namespace JibayMcs\FilamentTour\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string openTour(string $id): string
 *
 * @see \JibayMcs\FilamentTour\FilamentTour
 */
class FilamentTour extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'FilamentTour';
    }
}
