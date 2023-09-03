<?php

namespace JibayMcs\FilamentTour\Livewire;

use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Resource;
use JibayMcs\FilamentTour\FilamentTourPlugin;
use JibayMcs\FilamentTour\Highlight\HasHighlight;
use JibayMcs\FilamentTour\Tour\HasTour;
use Livewire\Attributes\On;
use Livewire\Component;

class FilamentTourWidget extends Component implements HasForms
{
    use InteractsWithForms;

    public array $tours = [];

    public array $highlights = [];


    #[On('filament-tour::load-elements')]
    public function load(array $request): void
    {

        // Use to prevent loading tours and highlights if user is not logged in
        if (!auth()->check())
            return;

        $classesUsingHasTour = [];
        $classesUsingHasHighlight = [];
        $filamentClasses = [];

        foreach (array_merge(Filament::getResources(), Filament::getPages()) as $class) {
            $instance = new $class;

            if ($instance instanceof Resource) {
                collect($instance->getPages())->map(fn($item) => $item->getPage())
                    ->flatten()
                    ->each(function ($item) use (&$filamentClasses) {
                        $filamentClasses[] = $item;
                    });
            } else {
                $filamentClasses[] = $class;
            }

        }

        foreach ($filamentClasses as $class) {
            $traits = class_uses($class);

            if (in_array(HasTour::class, $traits)) {
                $classesUsingHasTour[] = $class;
            }

            if (in_array(HasHighlight::class, $traits)) {
                $classesUsingHasHighlight[] = $class;
            }
        }

        foreach ($classesUsingHasTour as $class) {
            $this->tours = array_merge($this->tours, (new $class())->constructTours($class, $request));
        }

        foreach ($classesUsingHasHighlight as $class) {
            $this->highlights = array_merge($this->highlights, (new $class())->constructHighlights($class, $request));
        }

        $this->dispatch('filament-tour::loaded-elements',
            only_visible_once: is_bool(FilamentTourPlugin::get()->isOnlyVisibleOnce()) ? FilamentTourPlugin::get()->isOnlyVisibleOnce() : config('filament-tour.only_visible_once'),
            tours: $this->tours,
            highlights: $this->highlights,
        );

//        if (config('app.env') != 'production') {
        $hasCssSelector = is_bool(FilamentTourPlugin::get()->isCssSelectorEnabled()) ? FilamentTourPlugin::get()->isCssSelectorEnabled() : config('filament-tour.enable_css_selector');
        $this->dispatch('filament-tour::change-css-selector-status', enabled: $hasCssSelector);
//        }
    }

    public function render()
    {
        return view('filament-tour::livewire.filament-tour-widget');
    }
}
