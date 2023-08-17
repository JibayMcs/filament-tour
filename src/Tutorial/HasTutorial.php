<?php

namespace App\Traits;

use App\Providers\Filament\AdminPanelProvider;
use App\Tutorial\Tutorial;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard;
use Filament\Pages\Page;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Resource;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use function PHPUnit\Framework\returnSelf;
use function PHPUnit\Framework\throwException;

trait HasTutorial
{

    public abstract function tutorials(): array;

    public function construct($class, Component $instance, array $request): array
    {
        $instance  = new $class;
        $tutorials = [];
        $route     = null;

        if (method_exists($instance, 'getResource')) {
            $resource = new ($instance->getResource());
            foreach ($resource->getPages() as $key => $page) {
                $route = $resource->getUrl($key);
            }
        } else {
            $route = $instance->getUrl();
        }


        foreach ($this->tutorials() as $tutorial) {
            $steps = json_encode(collect($tutorial->steps)->mapWithKeys(function ($key, $item) {

                $data[$item] = [
                    'popover' => [
                        'title' => view('tutorial.popover.title')
                            ->with('title', $key->title)
                            ->with('icon', $key->icon)
                            ->with('iconColor', $key->iconColor)
                            ->render(),
                        'description' => $key->description,
                        'onNext' => $key->onNext ?? null,
                        'unclosable' => $key->unclosable,
                    ],
                ];


                if ($key->redirect) {
                    $data[$item] = array_merge($data[$item], ['redirect' => $key->redirect]);
                }

                if ($key->element) {
                    $data[$item] = array_merge($data[$item], ['element' => $key->element]);
                }

                return $data;
            })->toArray());

            if ($route && $steps) {


                $currentRoute = parse_url($route);

                if (!array_key_exists('path', $currentRoute))
                    $currentRoute['path'] = '/';

                /*if ($currentRoute['host'] === $request['host'] && $currentRoute['path'] === $request['pathname']) {
                    $openNow = true;
                } else {
                    $openNow = false;
                }*/

                $tutorials[] = [
                    'id' => "tutorial.{$tutorial->id}",
//                    'open' => $openNow,
                    'colors' => [
                        'light' => $tutorial->colors['light'],
                        'dark' => $tutorial->colors['dark']
                    ],
                    'steps' => $steps
                ];
            }
        }

        return $tutorials;
    }
}
