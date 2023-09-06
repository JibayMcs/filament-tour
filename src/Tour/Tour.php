<?php

namespace JibayMcs\FilamentTour\Tour;

use Closure;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Facades\Lang;
use JibayMcs\FilamentTour\Tour\Traits\CanReadJson;

class Tour
{
    use CanReadJson;
    use EvaluatesClosures;

    private string $id;

    private array $steps = [];

    private ?string $route = null;

    private array $colors = [];

    private bool $alwaysShow = false;

    private bool $visible = true;

    private bool $uncloseable = false;

    private bool $disableEvents = false;

    private bool $ignoreRoutes = false;

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
    public static function make(...$params): static
    {
        $params = collect($params);

        switch ($params->keys()->map(fn ($key) => $key)->toArray()[0]) {
            case 'url':
            case 'json':
                return self::fromJson($params->first());
            default:
                return app(static::class,
                    [
                        'id' => $params->first(),
                        'colors' => [
                            'dark' => '#fff',
                            'light' => 'rgb(0,0,0)',
                        ],
                    ]);
                break;
        }
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
            $this->alwaysShow = $this->evaluate($alwaysShow);
        }

        return $this;
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
            $this->visible = $this->evaluate($visible);
        }

        return $this;
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
            $this->uncloseable = $this->evaluate($uncloseable);
        }

        return $this;
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
            $this->disableEvents = $this->evaluate($disableEvents);
        }

        return $this;
    }

    /**
     * Bypass the route check to show your tour on any routes.
     *
     * @return $this
     */
    public function ignoreRoutes(bool|Closure $ignoreRoutes = true): self
    {

        if (is_bool($ignoreRoutes)) {
            $this->ignoreRoutes = $ignoreRoutes;
        } else {
            $this->ignoreRoutes = $this->evaluate($ignoreRoutes);
        }

        return $this;
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

    public function getId(): string
    {
        return $this->id;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function getSteps(): array
    {
        return $this->steps;
    }

    public function getColors(): array
    {
        return $this->colors;
    }

    public function isAlwaysShow(): bool
    {
        return $this->alwaysShow;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function getNextButtonLabel(): string
    {
        return $this->nextButtonLabel;
    }

    public function getPreviousButtonLabel(): string
    {
        return $this->previousButtonLabel;
    }

    public function getDoneButtonLabel(): string
    {
        return $this->doneButtonLabel;
    }

    public function isUncloseable(): bool
    {
        return $this->uncloseable;
    }

    public function hasDisabledEvents(): bool
    {
        return $this->disableEvents;
    }

    public function isRoutesIgnored(): bool
    {
        return $this->ignoreRoutes;
    }
}
