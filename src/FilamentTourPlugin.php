<?php

namespace JibayMcs\FilamentTour;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Facades\Blade;

class FilamentTourPlugin implements Plugin
{
    private ?bool $onlyVisibleOnce = null;

    private ?bool $enableCssSelector = null;

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

    public function enableCssSelector(bool|Closure $enableCssSelector = true): self
    {
        if (is_callable($enableCssSelector)) {
            $this->enableCssSelector = $enableCssSelector();
        } elseif (is_bool($enableCssSelector)) {
            $this->enableCssSelector = $enableCssSelector;
        }

        return $this;
    }

    public function register(Panel $panel): void
    {
        $panel
            ->renderHook('panels::body.start', fn () => Blade::render('<livewire:filament-tour-widget/>'));
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public function onlyVisibleOnce(bool|Closure $onlyVisibleOnce = true): self
    {
        if (is_callable($onlyVisibleOnce)) {
            $this->onlyVisibleOnce = $onlyVisibleOnce();
        } elseif (is_bool($onlyVisibleOnce)) {
            $this->onlyVisibleOnce = $onlyVisibleOnce;
        }

        return $this;
    }

    public function isOnlyVisibleOnce(): ?bool
    {
        return $this->onlyVisibleOnce;
    }

    public function isCssSelectorEnabled(): ?bool
    {
        return $this->enableCssSelector;
    }
}
