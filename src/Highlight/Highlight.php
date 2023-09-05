<?php

namespace JibayMcs\FilamentTour\Highlight;

use Closure;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;

class Highlight
{
    private string $parent;

    private string $id;

    private array $colors;

    private string $title;

    private null|string|HtmlString|View $description = null;

    private string $icon = 'heroicon-m-question-mark-circle';

    private string $iconColor = 'gray';

    private ?string $element = null;

    private string $position = 'top-left';

    public function __construct(string $id, array $colors, string $parent)
    {
        $this->id = $id;
        $this->colors = $colors;
        $this->parent = $parent;
    }

    /**
     * Create the instance of your highlight.
     * <br>
     * Define a **$parent** to be able to view this highlight button next to it
     */
    public static function make(string $id, string $parent): static
    {
        return app(static::class,
            [
                'id' => $id,
                'colors' => [
                    'dark' => '#fff',
                    'light' => 'rgb(0,0,0)',
                ],
                'parent' => $parent,
            ]);
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the element to highlight when you click on this highlight button.
     *
     * @return $this
     */
    public function element(string $element): self
    {
        $this->element = $element;

        return $this;
    }

    public function getElement(): ?string
    {
        return $this->element;
    }

    /**
     * Set the title of your highlight.
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
     * Set the description of your highlight.
     *
     * @return $this
     */
    public function description(string|Closure|HtmlString|View $description): self
    {
        $this->description = is_callable($description) ? $description() : ($description instanceof View ? $description->render() : $description);

        return $this;
    }

    public function getDescription(): HtmlString|string|View|null
    {
        return $this->description;
    }

    /**
     * Set the icon highlight button.
     * <br>
     * - **heroicon-m-question-mark-circle** by default
     *
     * @return $this
     */
    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * Set the icon color of your highlight button.
     * <br>
     * - **gray** by default
     *
     * @return $this
     */
    public function iconColor(string $color): self
    {
        $this->iconColor = $color;

        return $this;
    }

    public function getIconColor(): string
    {
        return $this->iconColor;
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
     * Set the position of your highlight button.
     * <br>
     * - **top-left** by default
     * <br>
     * - **top-right**
     * <br>
     * - **bottom-left**
     * <br>
     * - **bottom-right**
     *
     * @return $this
     */
    public function position(string $position): self
    {
        match ($position) {
            'top-right' => $this->position = 'top-right',
            'bottom-left' => $this->position = 'bottom-left',
            'bottom-right' => $this->position = 'bottom-right',
            default => $this->position = 'top-left',
        };

        return $this;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function getParent(): string
    {
        return $this->parent;
    }
}
