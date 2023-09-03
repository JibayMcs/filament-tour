<?php

namespace JibayMcs\FilamentTour\Tour;

use Closure;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;
use JibayMcs\FilamentTour\Tour\Step\StepEvent;

class Step
{
    use StepEvent;

    private string $title;
    private ?string $description = null;
    private ?string $icon = null;
    private ?string $iconColor = null;
    private bool $uncloseable = false;
    private ?string $element;

    public function __construct(string $element = null)
    {
        $this->element = $element;
    }

    /**
     * Create the instance of your step.
     * <br>
     * If no **$element** defined, the step will be shown as a modal.
     */
    public static function make(string $element = null): static
    {
        $static = app(static::class, ['element' => $element]);

        return $static;
    }

    public function getElement(): ?string
    {
        return $this->element;
    }


    /**
     * Set the title of your step.
     *
     * @return $this
     */
    public function title(string|Closure $title): self
    {
        $this->title = is_string($title) ? $title : $title();

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }


    /**
     * Set the description of your step.
     *
     * @return $this
     */
    public function description(string|Closure|HtmlString|View $description): self
    {
        if (is_callable($description)) {
            $this->description = $description();
        } elseif ($description instanceof HtmlString) {
            $this->description = $description->toHtml();
        } elseif ($description instanceof View) {
            $this->description = $description->render();
        } else {
            $this->description = $description;
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
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

    public function isUncloseable(): bool
    {
        return $this->uncloseable;
    }


    /**
     * Set the icon of your step, next to the title.
     *
     * @return $this
     */
    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }


    /**
     * Set the color of your icon.
     *
     * @return $this
     */
    public function iconColor(string $color): self
    {
        $this->iconColor = $color;

        return $this;
    }

    public function getIconColor(): ?string
    {
        return $this->iconColor;
    }


}
