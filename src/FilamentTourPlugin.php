<?php

namespace JibayMcs\FilamentTour;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Facades\Blade;

class FilamentTourPlugin implements Plugin
{
    private ?bool $onlyVisibleOnce = null;

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

    public function getId(): string
    {
        return 'filament-tour';
    }

    public function register(Panel $panel): void
    {
        $panel->renderHook('panels::body.start', fn () => Blade::render('<livewire:filament-tour-widget/>'));
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public function onlyVisibleOnce(bool $onlyVisibleOnce = true): self
    {
        $this->onlyVisibleOnce = $onlyVisibleOnce;

        return $this;
    }

    public function isOnlyVisibleOnce(): ?bool
    {
        return $this->onlyVisibleOnce;
    }
}
