<?php

namespace JibayMcs\FilamentTour\Tour;

class Tour
{
    public string $id;

    public array $steps = [];

    public ?string $route = null;

    public array $colors = [];

    public function __construct(string $id, array $colors)
    {
        $this->id = $id;
        $this->colors = $colors;
    }

    public static function make(string $id): static
    {
        $static = app(static::class,
            [
                'id' => $id,
                'colors' => [
                    'dark' => 'rgb(var(--gray-600))',
                    'light' => 'rgb(0,0,0)',
                ],
            ]);

        return $static;
    }

    public function onlyOn(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function steps(Step ...$steps): self
    {
        $this->steps = $steps;

        return $this;
    }

    public function colors(string $light, string $dark): self
    {
        $this->colors = [
            'light' => $light,
            'dark' => $dark,
        ];

        return $this;
    }
}
