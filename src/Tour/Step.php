<?php

namespace JibayMcs\FilamentTour\Tour;

use Closure;
use Exception;
use Filament\Notifications\Notification;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;
use JibayMcs\FilamentTour\Tour\Step\StepEvent;
use Throwable;

class Step
{
    use EvaluatesClosures;
    use StepEvent;

    private ?string $element;

    private string $title;

    private ?string $description = null;

    private ?string $icon = null;

    private ?string $iconColor = null;

    private bool $uncloseable = false;

    public function __construct(string $element = null)
    {
        $this->element = $element;
    }

    public static function fromArray(array $array): static
    {
        $step = $array;

        $app = app(static::class, ['element' => $step->element ?? null]);

        $app->title($step['title']);
        $app->description($step['description']);
        $app->icon($step['icon'] ?? null);
        $app->iconColor($step['iconColor'] ?? null);
        $app->uncloseable($step['uncloseable'] ?? false);

        if ($step['events']['dispatchOnNext'])
            $app->dispatchOnNext($step['events']['dispatchOnNext'][0], ...$step['events']['dispatchOnNext'][1]);

        if ($step['events']['notifyOnNext'])
            $app->notifyOnNext(
                Notification::make(uniqid())
                    ->title($step['events']['notifyOnNext']['title'])
                    ->body($step['events']['notifyOnNext']['body'] ?? null)
                    ->color($step['events']['notifyOnNext']['color'] ?? null)
                    ->icon($step['events']['notifyOnNext']['icon'] ?? null)
                    ->iconColor($step['events']['notifyOnNext']['iconColor'] ?? null)
                    ->iconSize($step['events']['notifyOnNext']['iconSize'] ?? null)
                    ->actions($step['events']['notifyOnNext']['actions'] ?? [])
                    ->duration($step['events']['notifyOnNext']['duration'] ?? 6000)
            );

        if ($step['events']['clickOnNext'])
            $app->clickOnNext($step['events']['clickOnNext']);

        if ($step['events']['redirectOnNext']) {
            if (is_array($step['events']['redirectOnNext'])) {
                $app->redirectOnNext($step['events']['redirectOnNext']['url'], isset($step['events']['redirectOnNext']['newTab']) ? $step['events']['redirectOnNext']['newTab'] : true);
            } else {
                $app->redirectOnNext($step['events']['redirectOnNext']);
            }
        }

        return $app;
    }

    /**
     * Set the title of your step.
     *
     * @return $this
     */
    public function title(string|Closure $title): self
    {
        $this->title = is_string($title) ? $title : $this->evaluate($title);

        return $this;
    }

    /**
     * Set the description of your step.
     *
     * @return $this
     */
    public function description(string|Closure|HtmlString|View $description): self
    {
        try {
            if (is_callable($description)) {
                $this->description = $this->evaluate($description);
            } elseif (method_exists($description, 'toHtml')) {
                $this->description = $description->toHtml();
            } elseif (method_exists($description, 'render')) {
                $this->description = $description->render();
            } else {
                $this->description = $description;
            }
        } catch (Throwable $e) {
            throw new Exception("Unable to evaluate description.\n{$e->getMessage()}");
        }

        return $this;
    }

    /**
     * Set the icon of your step, next to the title.
     *
     * @return $this
     */
    public function icon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Set the color of your icon.
     *
     * @return $this
     */
    public function iconColor(?string $color): self
    {
        $this->iconColor = $color;

        return $this;
    }

    /**
     * Set the step as uncloseable.
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

    /**
     * Create the instance of your step.
     * <br>
     * If no **$element** defined, the step will be shown as a modal.
     */
    public static function make(string $element = null): static
    {
        return app(static::class, ['element' => $element]);
    }

    public function getElement(): ?string
    {
        return $this->element;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getIconColor(): ?string
    {
        return $this->iconColor;
    }

    public function isUncloseable(): bool
    {
        return $this->uncloseable;
    }
}
