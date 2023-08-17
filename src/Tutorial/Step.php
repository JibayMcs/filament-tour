<?php

namespace App\Tutorial;

use Illuminate\Support\HtmlString;
use Illuminate\View\View;

class Step
{
    public string|\Closure $title;

    public null|string|\Closure|HtmlString|View $description = null;

    public ?string $icon = null;

    public ?string $iconColor = null;

    public ?string $element = null;

    public $onNext = null;

    public ?array $redirect = null;

    public bool $unclosable = false;

    public function __construct(string|\Closure $title, string|\Closure|HtmlString|View $description = null)
    {
        $this->title = is_callable($title) ? $title() : $title;
        $this->description = is_callable($description) ? $description() : ($description instanceof View ? $description->render() : $description);
    }

    public static function make(string|\Closure $title, string|\Closure|HtmlString|View $description = null): static
    {
        $static = app(static::class, ['title' => $title, 'description' => $description]);

        return $static;
    }

    public function unclosable(bool $unclosable = true): self
    {
        $this->unclosable = $unclosable;

        return $this;
    }

    public function skippable(bool $skippable = true): self
    {
        $this->skippable = $skippable;

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

    public function element($value): self
    {
        $this->element = $value;

        return $this;
    }

    public function onNext(\Closure $closure): self
    {
        $this->onNext = $closure();

        return $this;
    }

    public function redirect(string $url, bool $newTab = false): self
    {
        $this->redirect = ['url' => $url, 'newTab' => $newTab];

        return $this;
    }
}
