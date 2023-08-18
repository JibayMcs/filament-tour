<?php

namespace JibayMcs\FilamentTour\Highlight;

use Illuminate\Support\HtmlString;
use Illuminate\View\View;

class Highlight
{
    public string $parent;

    public string $id;

    public array $colors;

    public string|\Closure $title;

    public null|string|\Closure|HtmlString|View $description = null;

    public ?string $icon = null;

    public string $iconColor = 'gray';

    public ?string $element = null;

    public string $position = 'top-left';

    public function __construct(string $id, array $colors, string $parent)
    {
        $this->id = $id;
        $this->colors = $colors;
        $this->parent = $parent;
    }

    public static function make(string $parent): static
    {
        $static = app(static::class,
            [
                'id' => uniqid(),
                'colors' => [
                    'dark' => 'rgb(var(--gray-600))',
                    'light' => 'rgb(0,0,0)',
                ],
                'parent' => $parent,
            ]);

        return $static;
    }

    public function element(string $element): self
    {
        $this->element = $element;

        return $this;
    }

    public function title(string|\Closure $title): self
    {
        $this->title = is_callable($title) ? $title() : $title;

        return $this;
    }

    public function description(string|\Closure|HtmlString|View $description = null): self
    {
        $this->description = is_callable($description) ? $description() : ($description instanceof View ? $description->render() : $description);

        return $this;
    }

    public function icon($icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function iconColor($color): self
    {
        $this->iconColor = $color;

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

    public function position(string $position): self
    {
        match ($position) {
            'top-left' => $this->position = 'top-left',
            'top-right' => $this->position = 'top-right',
            'bottom-left' => $this->position = 'bottom-left',
            'bottom-right' => $this->position = 'bottom-right',
            default => $this->position = 'top-left',
        };

        return $this;
    }
}
