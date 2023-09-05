<?php

namespace JibayMcs\FilamentTour\Tour\Traits;

use Filament\Facades\Filament;

trait CanConstructRoute
{
    private array|false|int|null|string $route = null;

    public function getRoute($class): array|false|int|null|string
    {
        $instance = new $class;

        if ($this->route != null) {
            return $this->route;
        }

        if (Filament::getCurrentPanel()->getTenantModel()) {

            $tenants = Filament::getCurrentPanel()->getTenantModel()::find(Filament::auth()->user()->getTenants(Filament::getCurrentPanel()));

            $tenant = $tenants->first();

            $slug = $tenant->slug;
            if ($slug) {
                $this->route = parse_url($instance->getUrl(['tenant' => $slug]))['path'];
            }
        } else {
            if (method_exists($instance, 'getResource')) {
                $resource = new ($instance->getResource());
                foreach ($resource->getPages() as $key => $page) {
                    if ($class === $page->getPage()) {
                        $this->route = parse_url($resource->getUrl($key))['path'];
                    }
                }
            } else {
                $this->route = parse_url($instance->getUrl())['path'];
            }
        }

        return $this->route;
    }

    public function setRoute(string $route)
    {
        $this->route = $route;
    }
}
