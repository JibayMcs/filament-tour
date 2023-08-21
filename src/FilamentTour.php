<?php

namespace JibayMcs\FilamentTour;

class FilamentTour
{
    public function openTour(string $id): string
    {
        return "\$dispatch('driverjs::open-tour', 'tour.$id');";
    }

    public function openHighlight(string $id): string
    {
        return "\$dispatch('driverjs::open-highlight', 'highlight.$id');";
    }
}
