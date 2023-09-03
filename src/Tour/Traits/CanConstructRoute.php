<?php

namespace JibayMcs\FilamentTour\Tour\Traits;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

trait CanConstructRoute
{
    private array|false|int|null|string $route = null;

    public function getRoute($instance, $class): array|false|int|null|string
    {
        $panelPath = Filament::getCurrentPanel()->getPath();

        if ($this->route != null) {
            return $this->route;
        }

        if ($tenant = $this->panelTenant()) {
            $slug = Filament::getCurrentPanel()->getTenantSlugAttribute();
            if ($slug) {
                $this->route = "$panelPath/{$tenant->{$slug}}";
            }
        }

        if (method_exists($instance, 'getResource')) {
            $resource = new ($instance->getResource());
            foreach ($resource->getPages() as $key => $page) {
                if ($page->getPage() === $class) {
                    $this->route = parse_url($resource->getUrl($key))['path'];
                }
            }
        } else {
            $this->route = parse_url($instance->getUrl())['path'];
        }

        return $this->route;
    }

    public function setRoute(string $route)
    {
        $this->route = $route;
    }

    private function panelTenant(): bool|Model
    {
        return Filament::getTenant() ?? false;
    }
}
