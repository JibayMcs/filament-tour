<?php

namespace JibayMcs\FilamentTour\Tour;

use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;

class Step
{
    public string|\Closure $title;

    public null|string|\Closure|HtmlString|View $description = null;

    public ?string $icon = null;

    public ?string $iconColor = null;

    public ?string $element = null;

    public ?string $onNextClickSelector = null;

    public ?Notification $notification = null;

    public ?array $dispatch = null;

    public ?array $redirect = null;

    public bool $unclosable = false;

    public function __construct(string $element = null)
    {
        $this->element = $element;
    }

    public static function make(string $element = null): static
    {
        $static = app(static::class, ['element' => $element]);

        return $static;
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

    public function onNextClick(string $selector): self
    {
        $this->onNextClickSelector = $selector;

        return $this;
    }

    public function notify(Notification $notification): self
    {
        $this->notification = $notification;

        return $this;
    }

    public function redirect(string $url, bool $newTab = false): self
    {
        $this->redirect = ['url' => $url, 'newTab' => $newTab];

        return $this;
    }

    public function onNextDispatch(string $name, ...$args): self
    {
        $this->dispatch = ['name' => $name, 'args' => json_encode($args)];

        return $this;
    }
}
