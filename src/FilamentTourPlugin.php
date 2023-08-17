<?php

namespace JibayMcs\FilamentTour;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Facades\Blade;

class FilamentTourPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-tour';
    }

    public function register(Panel $panel): void
    {
        $panel->renderHook('panels::body.start', fn () => Blade::render('<livewire:tutorial-widget/>'));
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
