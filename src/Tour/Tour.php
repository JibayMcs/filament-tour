<?php

namespace JibayMcs\FilamentTour\Tour;

use Closure;
use Illuminate\Support\Facades\Lang;

class Tour
{
    private string $id;

    private array $steps = [];

    private ?string $route = null;

    private array $colors = [];

    private bool $alwaysShow = false;

    private bool $visible = true;

    private bool $uncloseable = false;

    private bool $disableEvents = false;

    private bool $ignoreRoute = false;

    private string $nextButtonLabel;

    private string $previousButtonLabel;

    private string $doneButtonLabel;

    public function __construct(string $id, array $colors)
    {
        $this->id = $id;
        $this->colors = $colors;

        $this->nextButtonLabel = Lang::get('filament-tour::filament-tour.button.next');
        $this->previousButtonLabel = Lang::get('filament-tour::filament-tour.button.previous');
        $this->doneButtonLabel = Lang::get('filament-tour::filament-tour.button.done');
    }

    /**
     * Create the instance of your tour.
     * <br>
     * Define an **$id** to be able to call it later in a livewire event.
     */
    public static function make(string $id): static
    {
        return app(static::class,
            [
                'id' => $id,
                'colors' => [
                    'dark' => '#fff',
                    'light' => 'rgb(0,0,0)',
                ],
            ]);
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the route where the tour will be shown.
     *
     * @return $this
     */
    public function route(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    /**
     * Set the steps of your tour.
     *
     * @return $this
     */
    public function steps(Step ...$steps): self
    {
        $this->steps = $steps;

        return $this;
    }

    public function getSteps(): array
    {
        return json_decode(json_encode($this->steps), true);
    }

    /**
     * Set the colors of your background highlighted elements, based on your current filament theme.
     * <br>
     *  - **rgb(0,0,0)** by default for **$light**
     * <br>
     * - **rgb(var(--gray-600))** by default for **$dark**
     *
     * @return $this
     */
    public function colors(string $light, string $dark): self
    {
        $this->colors = [
            'light' => $light,
            'dark' => $dark,
        ];

        return $this;
    }

    public function getColors(): array
    {
        return $this->colors;
    }

    /**
     * Set the tour as always visible, even is already viewed by the user.
     *
     * @return $this
     */
    public function alwaysShow(bool|Closure $alwaysShow = true): self
    {
        if (is_bool($alwaysShow)) {
            $this->alwaysShow = $alwaysShow;
        } else {
            $this->alwaysShow = $alwaysShow();
        }

        return $this;
    }

    public function isAlwaysShow(): bool
    {
        return $this->alwaysShow;
    }

    /**
     * Set the tour as visible or not.
     *
     * @return $this
     */
    public function visible(bool|Closure $visible = true): self
    {
        if (is_bool($visible)) {
            $this->visible = $visible;
        } else {
            $this->visible = $visible();
        }

        return $this;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * Set the label of the next button.
     *
     * @return $this
     */
    public function nextButtonLabel(string $label): self
    {
        $this->nextButtonLabel = $label;

        return $this;
    }

    public function getNextButtonLabel(): string
    {
        return $this->nextButtonLabel;
    }

    /**
     * Set the label of the previous button.
     *
     * @return $this
     */
    public function previousButtonLabel(string $label): self
    {
        $this->previousButtonLabel = $label;

        return $this;
    }

    public function getPreviousButtonLabel(): string
    {
        return $this->previousButtonLabel;
    }

    /**
     * Set the label of the done button.
     *
     * @return $this
     */
    public function doneButtonLabel(string $label): self
    {
        $this->doneButtonLabel = $label;

        return $this;
    }

    public function getDoneButtonLabel(): string
    {
        return $this->doneButtonLabel;
    }

    /**
     * Set the tour steps uncloseable.
     *
     * @return $this
     */
    public function uncloseable(bool|Closure $uncloseable = true): self
    {
        if (is_bool($uncloseable)) {
            $this->uncloseable = $uncloseable;
        } else {
            $this->uncloseable = $uncloseable();
        }

        return $this;
    }

    public function isUncloseable(): bool
    {
        return $this->uncloseable;
    }

    /**
     * Disable all events on the tour.
     * default: false
     *
     * @return $this
     */
    public function disableEvents(bool|Closure $disableEvents = true): self
    {
        if (is_bool($disableEvents)) {
            $this->disableEvents = $disableEvents;
        } else {
            $this->disableEvents = $disableEvents();
        }

        return $this;
    }

    public function hasDisabledEvents(): bool
    {
        return $this->disableEvents;
    }

    /**
     * Bypass the route check to show your tour on any routes.
     *
     * @return $this
     */
    public function ignoreRoute(bool $ignoreRoute): self
    {
        $this->ignoreRoute = $ignoreRoute;

        return $this;
    }

    public function isRoutesIgnored(): bool
    {
        return $this->ignoreRoute;
    }
}
