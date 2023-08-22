<?php

namespace JibayMcs\FilamentTour\Tour;

use JibayMcs\FilamentTour\Traits\CanConstructRoute;

trait HasTour
{
    use CanConstructRoute;

    public function constructTours($class, $request): array
    {
        $instance = new $class;
        $tours    = [];

        foreach ($this->tours() as $tour) {

            if ($tour->route) {
                $this->setRoute($tour->route);
            }

            $steps = json_encode(collect($tour->steps)->mapWithKeys(function ($key, $item) {
                $data[$item] = [
                    'onNextRedirect' => $key->onNextRedirect,

                    'onNextClickSelector' => $key->onNextClickSelector ?? null,
                    'onNextNotify' => $key->onNextNotify,
                    'onNextDispatch' => $key->onNextDispatch,
                    'uncloseable' => $key->uncloseable,

                    'popover' => [
                        'title' => view('filament-tour::tour.step.popover.title')
                            ->with('title', $key->title)
                            ->with('icon', $key->icon)
                            ->with('iconColor', $key->iconColor)
                            ->render(),
                        'description' => $key->description,
                    ],
                ];

                if ($key->element) {
                    $data[$item]['element'] = $key->element;
                }

                return $data;
            })->toArray());

            if ($steps) {

                if ($request['pathname'] == ($this->getRoute($instance, $class)['path'] ?? '/')) {

                    $tours[] = [
                        'route' => $this->getRoute($instance, $class)['path'] ?? '/',
                        'id' => "tour.{$tour->id}",
                        'alwaysShow' => $tour->alwaysShow,
                        'colors' => [
                            'light' => $tour->colors['light'],
                            'dark' => $tour->colors['dark'],
                        ],
                        'steps' => $steps,
                        'nextButtonLabel' => $tour->nextButtonLabel,
                        'previousButtonLabel' => $tour->previousButtonLabel,
                        'doneButtonLabel' => $tour->doneButtonLabel,
                    ];

                }
            }
        }

        return $tours;
    }

    /**
     * Define your tours here.
     */
    abstract public function tours(): array;
}
