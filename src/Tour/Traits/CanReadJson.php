<?php

namespace JibayMcs\FilamentTour\Tour\Traits;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use JibayMcs\FilamentTour\Tour\Step;

trait CanReadJson
{
    public static function fromJson(string $json): static
    {
        $tour = self::readJson($json);
        
        $steps = [];

        $app = app(static::class,
            [
                'id' => $tour['id'],
                'colors' => [
                    'dark' => '#fff',
                    'light' => 'rgb(0,0,0)',
                ],
            ]);

        $app->route($tour['route'] ?? null);

        if (isset($tour['colors'])) $app->colors($tour['colors'][0] ?? "rgb(0,0,0)", $tour['colors'][1] ?? "#fff");

        $app->alwaysShow($tour['alwaysShow'] ?? false);

        $app->visible($tour['visible'] ?? true);

        $app->uncloseable($tour['uncloseable'] ?? false);

        $app->disableEvents($tour['disableEvents'] ?? false);

        $app->ignoreRoutes($tour['ignoreRoutes'] ?? false);

        $app->nextButtonLabel($tour['nextButtonLabel'] ?? Lang::get('filament-tour::filament-tour.button.next'));

        $app->previousButtonLabel($tour['previousButtonLabel'] ?? Lang::get('filament-tour::filament-tour.button.previous'));

        $app->doneButtonLabel($tour['doneButtonLabel'] ?? Lang::get('filament-tour::filament-tour.button.done'));

        foreach ($tour['steps'] as $step) {
            $steps[] = Step::fromArray($step);
        }

        if (isset($tour['steps'])) $app->steps(...$steps);

        return $app;
    }

    private static function readJson(string $json): array
    {
        if (filter_var($json, FILTER_VALIDATE_URL)) {
            $jsonContent = file_get_contents($json);
            if ($jsonContent !== false) {
                $data = json_decode($jsonContent, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $data;
                } else {
                    Notification::make('error_parsing_json_from_url')
                        ->title("Error parsing Tour from JSON as URL")
                        ->danger()
                        ->send();
                }
            } else {
                Notification::make('error_parsing_url')
                    ->title("Unable to parse URL for JSON Tour")
                    ->body($json)
                    ->danger()
                    ->send();
            }
        } else {
            $jsonData = json_decode($json, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $jsonData;
            } else {
                Notification::make('error_parsing_json')
                    ->title("Error parsing Tour from JSON")
                    ->body("Verify if your JSON file is valid or exists")
                    ->danger()
                    ->send();
            }
        }

        return [];
    }

}
