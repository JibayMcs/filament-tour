<?php

namespace JibayMcs\FilamentTour\Commands;

use Illuminate\Console\Command;

class FilamentTourCommand extends Command
{
    public $signature = 'filament-tour';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
