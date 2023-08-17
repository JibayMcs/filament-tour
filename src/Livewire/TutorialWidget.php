<?php

namespace JibayMcs\FilamentTour\Livewire;

use Filament\Facades\Filament;
use Filament\Resources\Resource;
use JibayMcs\FilamentTour\Tour\HasTour;
use Livewire\Attributes\On;
use Livewire\Component;

class TutorialWidget extends Component
{
    public array $tutorials = [];

    #[On('driverjs::load-tutorials')]
    public function loadTutorials(array $request): void
    {
        $classesUsingHasTutorial = [];
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
                $classesUsingHasTutorial[] = $class;
            }
        }

        foreach ($classesUsingHasTutorial as $class) {
            foreach ((new $class())->construct($class, $request) as $tutorial) {
                $this->tutorials[] = $tutorial;
            }
        }

        $this->dispatch('driverjs::loaded-tutorials', ['tutorials' => $this->tutorials]);

    }

    public function render()
    {
        return view('filament-tour::livewire.tutorial-widget');
    }
}
