<?php

namespace JibayMcs\FilamentTour\Traits;

trait CanConstructRoute
{
    private array|false|int|null|string $route = null;

    public function getRoute($instance, $class): array|false|int|null|string
    {

        if ($this->route != null) {
            return $this->route;
        }

        if (method_exists($instance, 'getResource')) {
            $resource = new ($instance->getResource());
            foreach ($resource->getPages() as $key => $page) {
                dd($page);
                if ($page->getPage() === $class) {
                    $this->route = parse_url($resource->getUrl($key));
                }
            }
        } else {
            $this->route = parse_url($instance->getUrl());
        }

        return $this->route;
    }

    public function setRoute(string $route)
    {
        $this->route = $route;
    }
}
