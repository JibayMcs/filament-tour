<?php

namespace JibayMcs\FilamentTour;

use JibayMcs\FilamentTour\Livewire\FilamentTourWidget;
use Livewire\LivewireManager;
use Livewire\Mechanisms\ComponentRegistry;

class FilamentTour
{
    public function openTour(string $id)
    {
        if (app(LivewireManager::class)->current())
            app(LivewireManager::class)->current()->dispatch('driverjs::open-tour', $id);
    }
}
