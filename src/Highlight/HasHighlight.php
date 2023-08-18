<?php

namespace JibayMcs\FilamentTour\Highlight;

trait HasHighlight
{
    public function highlights(): array
    {
        return [];
    }

    public function constructHighlights($class, array $request): array
    {

        $instance = new $class;

        return collect($this->highlights())->mapWithKeys(function ($key, $item) {
            $data[$item] = [
                'id' => "highlight.{$key->id}",
                'position' => $key->position,
                'parent' => $key->parent,
                'button' => view('filament-tour::highlight.button')
                    ->with('id', "highlight.{$key->id}")
                    ->with('icon', $key->icon ?? 'heroicon-m-question-mark-circle')
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
}
