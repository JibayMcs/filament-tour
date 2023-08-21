<?php

namespace JibayMcs\FilamentTour;

use Livewire\LivewireManager;

class FilamentTour
{
    public function openTour(string $id)
    {
        if (app(LivewireManager::class)->current()) {
            app(LivewireManager::class)->current()->dispatch('driverjs::open-tour', $id);
        }
    }
}
