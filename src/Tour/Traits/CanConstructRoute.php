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

        if (! Filament::auth()->user()) {
            return '/';
        }

        if (Filament::getCurrentPanel()->getTenantModel()) {

            $tenants = Filament::auth()->user()->getTenants(Filament::getCurrentPanel());
            
            // Handle the case where getTenants returns a Collection of models
            if ($tenants instanceof \Illuminate\Support\Collection) {
                $tenant = $tenants->first();
            } else {
                // Handle the case where getTenants returns an array of IDs
                $tenantIds = $tenants;
                
                // Flatten the array to ensure it's not nested
                if (is_array($tenantIds)) {
                    $tenantIds = collect($tenantIds)->flatten()->filter()->toArray();
                }

                $tenants = Filament::getCurrentPanel()->getTenantModel()::find($tenantIds);
                $tenant = $tenants->first();
            }

            if ($tenant) {
                $this->route = parse_url($instance->getUrl(['tenant' => $tenant]))['path'];
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
                $this->route = parse_url($instance->getUrl())['path'] ?? '/';
            }

        }

        return $this->route;
    }

    public function setRoute(string $route)
    {
        $this->route = $route;
    }
}
