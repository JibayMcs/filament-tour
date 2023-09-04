<?php

namespace JibayMcs\FilamentTour\Livewire;

use Filament\Facades\Filament;
use Filament\Resources\Resource;
use JibayMcs\FilamentTour\FilamentTourPlugin;
use JibayMcs\FilamentTour\Tour\HasTour;
use Livewire\Attributes\On;
use Livewire\Component;

class FilamentTourWidget extends Component /*implements HasForms*/
{
    //    use InteractsWithForms;

    public array $steps = [];

    //    public array $highlights = [];

    //    public ?array $data = [];

    public int $currentStepIndex = 0;

    public function mount(): void
    {
        $request = request()->getPathInfo();

        if (! auth()->check()) {
            return;
        }

        $classesUsingHasTour = [];
        //        $classesUsingHasHighlight = [];
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

            /* if (in_array(HasHighlight::class, $traits)) {
                 $classesUsingHasHighlight[] = $class;
             }*/
        }

        foreach ($classesUsingHasTour as $class) {
            $this->steps = array_merge($this->steps, (new $class())->constructSteps($class, $request));
        }

        //        dd($this->steps);
        /* foreach ($classesUsingHasHighlight as $class) {
             $this->highlights = array_merge($this->highlights, (new $class())->constructHighlights($class, $request));
         }*/

        /*$this->dispatch('filament-tour::loaded-elements',
            only_visible_once: is_bool(FilamentTourPlugin::get()->isOnlyVisibleOnce()) ? FilamentTourPlugin::get()->isOnlyVisibleOnce() : config('filament-tour.only_visible_once'),
            tours: $this->tours,
            highlights: $this->highlights,
        );*/

        //        dd(json_decode($this->tours[0]['steps'], true)[0]);

        //        if (config('app.env') != 'production') {
        $hasCssSelector = is_bool(FilamentTourPlugin::get()->isCssSelectorEnabled()) ? FilamentTourPlugin::get()->isCssSelectorEnabled() : config('filament-tour.enable_css_selector');
        $this->dispatch('filament-tour::change-css-selector-status', enabled: $hasCssSelector);
        //        }
    }

    #[On('filament-tour::load-elements')]
    public function load(): void
    {
        $this->dispatch('filament-tour::updateStep', step: $this->steps[0]);
    }

    public function render()
    {
        return view('filament-tour::livewire.filament-tour-widget');
    }

    /*public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema(json_encode($this->steps[0]['schema']));
    }*/

    public function nextStep()
    {
        $totalSteps = count($this->steps);
        if ($this->currentStepIndex < count($this->steps) - 1) {
            $this->currentStepIndex++;
            $this->dispatch('filament-tour::updateStep', step: $this->steps[$this->currentStepIndex]);
        } elseif ($this->currentStepIndex == $totalSteps - 1) {
            $this->close();
        }
    }

    public function close()
    {
        $this->dispatch('filament-tour::close');
    }

    public function previousStep()
    {
        if ($this->currentStepIndex > 0) {
            $this->currentStepIndex--;
            $this->dispatch('filament-tour::updateStep', step: json_decode($this->tours[0]['steps'], true)[$this->currentStepIndex]);
        }
    }
}
