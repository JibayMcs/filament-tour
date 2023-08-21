<?php

namespace JibayMcs\FilamentTour\Livewire;

use Filament\Facades\Filament;
use Filament\Resources\Resource;
use JibayMcs\FilamentTour\FilamentTourPlugin;
use JibayMcs\FilamentTour\Highlight\HasHighlight;
use JibayMcs\FilamentTour\Tour\HasTour;
use Livewire\Attributes\On;
use Livewire\Component;

class FilamentTourWidget extends Component
{
    public array $tours = [];

    public array $highlights = [];

    #[On('driverjs::load-elements')]
    public function load(array $request): void
    {
        $classesUsingHasTour = [];
        $classesUsingHasHighlight = [];
        $filamentClasses = [];

        foreach (array_merge(Filament::getResources(), Filament::getPages()) as $class) {
            $instance = new $class;

            if ($instance instanceof Resource) {
                collect($instance->getPages())->map(fn ($item) => $item->getPage())
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

        $this->dispatch('driverjs::loaded-elements', [
            'only_visible_once' => is_bool(FilamentTourPlugin::get()->isOnlyVisibleOnce()) ? FilamentTourPlugin::get()->isOnlyVisibleOnce() : config('filament-tour.only_visible_once'),
            'tours' => $this->tours,
            'highlights' => $this->highlights,
        ]);

    }

    public function render()
    {
        return view('filament-tour::livewire.filament-tour-widget');
    }
}
