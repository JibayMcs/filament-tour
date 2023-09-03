<?php

namespace JibayMcs\FilamentTour\Tour\Step;

use Closure;
use Filament\Notifications\Notification;

trait StepEvent
{
    private ?string $clickOnNext = null;

    private ?array $notifyOnNext = null;

    private ?array $dispatchOnNext = null;

    private ?array $redirectOnNext = null;

    /**
     * Set the CSS selector to be clicked when the user clicks on the next button of your step.
     *
     * @return $this
     */
    public function clickOnNext(string|Closure $selector): self
    {
        if (is_callable($selector)) {
            $this->clickOnNext = $selector();
        } else {
            $this->clickOnNext = $selector;
        }

        return $this;
    }

    /**
     * Set the notification to be shown when the user clicks on the next button of your step.
     *
     * @return $this
     */
    public function notifyOnNext(Notification $notification): self
    {
        $this->notifyOnNext = $notification->toArray();

        return $this;
    }

    /**
     * Set the redirection to be done when the user clicks on the next button of your step.
     * <br>
     * You can choose to open the redirection in a new tab or not with **$newTab**, default false.
     *
     * @return $this
     */
    public function redirectOnNext(string $url, bool $newTab = false): self
    {
        $this->redirectOnNext = ['url' => $url, 'newTab' => $newTab];

        return $this;
    }

    /**
     * Set the liveire event to dispatch to, when the user clicks on the next button of your step.
     *
     * @param mixed ...$args
     * @return $this
     */
    public function dispatchOnNext(string $name, ...$args): self
    {
        $this->dispatchOnNext = ['name' => $name, 'args' => json_encode($args)];

        return $this;
    }

    public function getClickOnNext(): ?string
    {
        return $this->clickOnNext;
    }

    public function getNotifyOnNext(): ?array
    {
        return $this->notifyOnNext;
    }

    public function getDispatchOnNext(): ?array
    {
        return $this->dispatchOnNext;
    }

    public function getRedirectOnNext(): ?array
    {
        return $this->redirectOnNext;
    }


}
