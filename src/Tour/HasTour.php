<?php

namespace JibayMcs\FilamentTour\Tour;

use JibayMcs\FilamentTour\Tour\Traits\CanConstructRoute;

trait HasTour
{
    use CanConstructRoute;

    public function constructTours($class): array
    {
        $tours = [];

        foreach ($this->tours() as $tour) {

            if ($tour instanceof Tour) {

                if ($tour->getRoute()) {
                    $this->setRoute($tour->getRoute());
                }

                $steps = json_encode(collect($tour->getSteps())->mapWithKeys(function (Step $step, $item) use ($tour) {

                    $data[$item] = [
                        'uncloseable' => $step->isUncloseable(),

                        'popover' => [
                            'title' => view('filament-tour::tour.step.popover.title')
                                ->with('title', $step->getTitle())
                                ->with('icon', $step->getIcon())
                                ->with('iconColor', $step->getIconColor())
                                ->render(),
                            'description' => $step->getDescription(),
                        ],

                        'progress' => [
                            'current' => $item,
                            'total' => count($tour->getSteps()),
                        ],
                    ];

                    if (!$tour->hasDisabledEvents()) {
                        $data[$item]['events'] = [
                            'redirectOnNext' => $step->getRedirectOnNext(),
                            'clickOnNext' => $step->getClickOnNext(),
                            'notifyOnNext' => $step->getNotifyOnNext(),
                            'dispatchOnNext' => $step->getDispatchOnNext(),
                        ];
                    }

                    if ($step->getElement()) {
                        $data[$item]['element'] = $step->getElement();
                    }

                    return $data;
                })->toArray());

                if ($steps) {

                    $route = $this->getRoute($class);

                    $tours[] = [
                        'routesIgnored' => $tour->isRoutesIgnored(),
                        
                        'uncloseable' => $tour->isUncloseable(),

                        'route' => $route,

                        'id' => "tour_{$tour->getId()}",

                        'alwaysShow' => $tour->isAlwaysShow(),

                        'colors' => [
                            'light' => $tour->getColors()['light'],
                            'dark' => $tour->getColors()['dark'],
                        ],

                        'steps' => $steps,

                        'nextButtonLabel' => $tour->getNextButtonLabel(),
                        'previousButtonLabel' => $tour->getPreviousButtonLabel(),
                        'doneButtonLabel' => $tour->getDoneButtonLabel(),
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
