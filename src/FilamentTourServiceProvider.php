<?php

namespace JibayMcs\FilamentTour;

use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use JibayMcs\FilamentTour\Livewire\FilamentTourVideoPlayer;
use JibayMcs\FilamentTour\Livewire\FilamentTourWidget;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentTourServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-tour';

    public static string $viewNamespace = 'filament-tour';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasConfigFile(self::$name)
            ->hasTranslations()
            ->hasViews(static::$viewNamespace);
    }

    public function packageBooted(): void
    {
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        Livewire::component('filament-tour-widget', FilamentTourWidget::class);
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            Css::make('filament-tour-styles', __DIR__ . '/../resources/dist/filament-tour.css'),
            Js::make('filament-tour-scripts', __DIR__ . '/../resources/dist/filament-tour.js'),
        ];
    }

    protected function getAssetPackageName(): ?string
    {
        return 'jibaymcs/filament-tour';
    }
}
