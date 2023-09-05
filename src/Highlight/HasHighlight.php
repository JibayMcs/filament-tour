<?php

namespace JibayMcs\FilamentTour\Highlight;

use JibayMcs\FilamentTour\Tour\Traits\CanConstructRoute;

trait HasHighlight
{
    use CanConstructRoute;

    public function constructHighlights($class): array
    {
        return collect($this->highlights())->mapWithKeys(function (Highlight $highlight, $item) use ($class) {

            $data[$item] = [
                'route' => $this->getRoute($class),

                'id' => "highlight_{$highlight->getId()}",

                'position' => $highlight->getPosition(),

                'parent' => $highlight->getParent(),

                'button' => view('filament-tour::highlight.button')
                    ->with('id', "highlight_{$highlight->getId()}")
                    ->with('icon', $highlight->getIcon())
                    ->with('iconColor', $highlight->getIcon())
                    ->render(),

                'colors' => [
                    'light' => $highlight->getColors()['light'],
                    'dark' => $highlight->getColors()['dark'],
                ],

                'popover' => [

                    'title' => view('filament-tour::tour.step.popover.title')
                        ->with('title', $highlight->getTitle())
                        ->render(),

                    'description' => $highlight->getDescription(),
                ],
            ];

            if ($highlight->getElement()) {
                $data[$item]['element'] = $highlight->getElement();
            }

            return $data;

        })->toArray();
    }

    /**
     * Define your highlights here.
     */
    abstract public function highlights(): array;
}
