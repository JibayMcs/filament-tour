<?php

namespace JibayMcs\FilamentTour\Tour;

use Livewire\Component;

trait HasTour
{
    abstract public function tours(): array;

    public function construct($class, Component $instance, array $request): array
    {
        $instance  = new $class;
        $tutorials = [];
        $route     = null;

        if (method_exists($instance, 'getResource')) {
            $resource = new ($instance->getResource());
            foreach ($resource->getPages() as $key => $page) {
                $route = $resource->getUrl($key);
            }
        } else {
            $route = $instance->getUrl();
        }

        foreach ($this->tours() as $tour) {
            $steps = json_encode(collect($tour->steps)->mapWithKeys(function ($key, $item) {

                $data[$item] = [
                    'element' => $key->element,
                    'redirect' => $key->redirect ?? null,
                    'popover' => [
                        'title' => view('tutorial.popover.title')
                            ->with('title', $key->title)
                            ->with('icon', $key->icon)
                            ->with('iconColor', $key->iconColor)
                            ->render(),
                        'description' => $key->description,
                        'onNextClickSelector' => $key->onNextClickSelector ?? null,
                        'onNextNotifiy'=> $key->notification ? $key->notification->toArray() : null,
                        'onNextDispatch' => $key->dispatch ?? null,
                        'unclosable' => $key->unclosable,
                    ],
                ];


                return $data;
            })->toArray());

            if ($route && $steps) {

                $currentRoute = parse_url($route);

                if (!array_key_exists('path', $currentRoute)) {
                    $currentRoute['path'] = '/';
                }

                if ($currentRoute['host'] === $request['host'] && $currentRoute['path'] === $request['pathname']) {
                    $openNow = true;
                } else {
                    $openNow = false;
                }

                $tutorials[] = [
                    'id' => "tutorial.{$tour->id}",
                    'open' => $openNow,
                    'colors' => [
                        'light' => $tour->colors['light'],
                        'dark' => $tour->colors['dark'],
                    ],
                    'steps' => $steps,
                ];
            }
        }

        return $tutorials;
    }
}
