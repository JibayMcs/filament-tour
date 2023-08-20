<?php

namespace JibayMcs\FilamentTour\Tour;

use Closure;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;

class Step
{
    public ?string $element = null;

    public string $title;

    public ?string $description = null;

    public ?string $icon = null;

    public ?string $iconColor = null;

    public ?string $onNextClickSelector = null;

    public ?array $onNextNotify = null;

    public ?array $onNextDispatch = null;

    public ?array $onNextRedirect = null;

    public bool $uncloseable = false;

    public function __construct(string $element = null)
    {
        $this->element = $element;
    }

    /**
     * Create the instance of your step.
     * <br>
     * If no **$element** defined, the step will be shown as a modal.
     *
     * @param string|null $element
     * @return static
     */
    public static function make(string $element = null): static
    {
        return app(static::class, ['element' => $element]);
    }

    /**
     * Set the title of your step.
     *
     * @param string|Closure $title
     * @return $this
     */
    public function title(string|Closure $title): self
    {
        $this->title = is_string($title) ? $title : $title();

        return $this;
    }

    /**
     * Set the description of your step.
     *
     * @param string|Closure|HtmlString|View $description
     * @return $this
     */
    public function description(string|Closure|HtmlString|View $description): self
    {
        if (is_callable($description)) {
            $this->description = $description();
        } else if ($description instanceof HtmlString) {
            $this->description = $description->toHtml();
        } else if ($description instanceof View) {
            $this->description = $description->render();
        } else {
            $this->description = $description;
        }

        return $this;
    }

    /**
     * Set the step as uncloseable.
     *
     * @param bool|Closure $uncloseable
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
     * Set the icon of your step, next to the title.
     *
     * @param string $icon
     * @return $this
     */
    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Set the color of your icon.
     *
     * @param string $color
     * @return $this
     */
    public function iconColor(string $color): self
    {
        $this->iconColor = $color;

        return $this;
    }

    /**
     * Set the CSS selector to be clicked when the user clicks on the next button of your step.
     *
     * @param string|Closure $selector
     * @return $this
     */
    public function onNextClick(string|Closure $selector): self
    {
        if (is_callable($selector)) {
            $this->onNextClickSelector = $selector();
        } else {
            $this->onNextClickSelector = $selector;
        }

        return $this;
    }

    /**
     * Set the notification to be shown when the user clicks on the next button of your step.
     *
     * @param Notification $notification
     * @return $this
     */
    public function onNextNotify(Notification $notification): self
    {
        $this->onNextNotify = $notification->toArray();

        return $this;
    }

    /**
     * Set the redirection to be done when the user clicks on the next button of your step.
     * <br>
     * You can choose to open the redirection in a new tab or not with **$newTab**, default false.
     *
     * @param string $url
     * @param bool $newTab
     * @return $this
     */
    public function onNextRedirect(string $url, bool $newTab = false): self
    {
        $this->onNextRedirect = ['url' => $url, 'newTab' => $newTab];

        return $this;
    }

    /**
     * Set the liveire event to dispatch to, when the user clicks on the next button of your step.
     *
     * @param string $name
     * @param mixed ...$args
     * @return $this
     */
    public function onNextDispatch(string $name, ...$args): self
    {
        $this->onNextDispatch = ['name' => $name, 'args' => json_encode($args)];

        return $this;
    }
}
