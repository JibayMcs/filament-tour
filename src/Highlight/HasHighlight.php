<?php

namespace JibayMcs\FilamentTour\Highlight;

use JibayMcs\FilamentTour\Traits\CanConstructRoute;

trait HasHighlight
{
    use CanConstructRoute;

    /**
     * Define your highlights here.
     *
     * @return array
     */
    public abstract function highlights(): array;

    public function constructHighlights($class, array $request): array
    {
        $highlights = [];
        $instance   = new $class;

        if ($request['pathname'] == ($this->getRoute($instance, $class)['path'] ?? '/')) {

            $highlights = collect($this->highlights())->mapWithKeys(function ($key, $item) use ($instance, $class, $request) {

                $data[$item] = [
                    'route' => $this->getRoute($instance, $class)['path'] ?? '/',
                    'id' => "highlight.{$key->id}",
                    'position' => $key->position,
                    'parent' => $key->parent,
                    'button' => view('filament-tour::highlight.button')
                        ->with('id', "highlight.{$key->id}")
                        ->with('icon', $key->icon)
                        ->with('iconColor', $key->iconColor)
                        ->render(),
                    'icon' => $key->icon,
                    'iconColor' => $key->iconColor ?? null,
                    'colors' => [
                        'light' => $key->colors['light'],
                        'dark' => $key->colors['dark'],
                    ],
                    'popover' => [
                        'title' => view('filament-tour::tour.step.popover.title')
                            ->with('title', $key->title)
                            ->render(),
                        'description' => $key->description,
                    ],
                ];

                if ($key->element) {
                    $data[$item]['element'] = $key->element;
                }

                return $data;

            })->toArray();
        }

        return $highlights;
    }
}
