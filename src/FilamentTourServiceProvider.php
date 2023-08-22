<?php

namespace JibayMcs\FilamentTour;

use Filament\Facades\Filament;
use Filament\PluginServiceProvider;
use Illuminate\Support\Facades\Blade;
use JibayMcs\FilamentTour\Livewire\FilamentTourWidget;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;

class FilamentTourServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-tour';


    protected array $styles = [
        'filament-tour-css' => __DIR__ . '/../resources/dist/filament-tour.css',
    ];

    protected array $beforeCoreScripts = [
        'filament-tour-js' => __DIR__ . '/../resources/dist/filament-tour.js',
    ];

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasConfigFile('filament-tour')
            ->hasViews('filament-tour')
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        Livewire::component('filament-tour-widget', FilamentTourWidget::class);

        Filament::registerRenderHook('body.start', fn() => Blade::render('<livewire:filament-tour-widget/>'));
    }

}