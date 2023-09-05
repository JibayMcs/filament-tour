# Bring the power of DriverJs to your Filament panels and start a tour !

With the power of [DriverJS](https://driverjs.com) bring to your users an elegant way to discover your panels !

## Installation

You can install this filament plugin via composer:

For Filament V3.x

```bash
composer require jibaymcs/filament-tour:"^3.x"
```

For Filament V2.x

```bash
composer require jibaymcs/filament-tour:"^2.x"
```

You can publish the config file with:

```bash 
php artisan vendor:publish --tag="filament-tour-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-tour-views"
```

This is the contents of the published config file:

```php
 return [    
    "only_visible_once" => true,  
];
```

## Usage

```php
public function panel(Panel $panel) {
	return $panel->default()
		->[...]
		->plugins([ FilamentTourPlugin::make() ]);
}  
```

You can also enable or disable the check on the local storage if the current user have already seen the tour.

```php
// default  : true  
FilamentTourPlugin::make()->onlyVisibleOnce(false)  
```

# Start a tour !

Let's follow this example to add a tour to your dashboard page.

If you don't already have a customized dashboard, please refer to the following tutorial: [FIlamentPHP - Dashboard - Customizing the dashboard page](https://filamentphp.com/docs/3.x/panels/dashboard#customizing-the-dashboard-page)

- Use the correct trait to registers your tours !

```php
<?php  
namespace App\Filament\Pages;  
  
use JibayMcs\FilamentTour\Tour\Tour;  
  
class Dashboard extends FilamentDashboard {

    use HasTour;
    // ...  

	public function tours(): array    {    
		return []; 
    	}
}  
```

- Create a simple tour !

```php
public function tours(): array {
    return [
       Tour::make('dashboard')
           ->steps(
                           
               Step::make()
                   ->title("Welcome to your Dashboard !")
                   ->description(view('tutorial.dashboard.introduction')),
    
               Step::make('.fi-avatar')
                   ->title('Woaw ! Here is your avatar !')
                   ->description('You look nice !')
                   ->icon('heroicon-o-user-circle')
                   ->iconColor('primary')
           ),
    ];
}
```

# Tour.php

### Tour methods reference

```php
// Instanciate a tour, and provide an id, to trigger it later
Tour::make(string $id)

    // Define a custom url to trigger your tour 
    ->route(string $route)
    
    //Register the steps of your tour
    ->steps(Step ...$steps)
    
    // Define a color of your hightlight overlay for the dark and light theme of your filament panel
    ->colors(string $light, string $dark)
    
    //Set the tour as always visible, even is already viewed by the user.
    ->alwaysShow(bool|Closure $alwaysShow = true)
    
    // Set the tour visible or not
    ->visible(bool|Closure $visible = true)
    
    // Set the 'Next' button label
    ->nextButtonLabel(string $label)
    
    // Set the 'Previous' button label
    ->previousButtonLabel(string $label)
    
    // Set the 'Done' button label
    ->doneButtonLabel(string $label)
    
    // Set the whole steps of the tour as uncloseable
    ->uncloseable(bool|Closure $uncloseable = true)
    
    // Disable all tour steps events
    ->disableEvents(bool|Closure $disableEvents = true)
    
    // Bypass route check to show the tour on all pages
    // Maybe useless, but who knows ?
    ->ignoreRoutes(bool|Closure $ignoreRoutes = true)
```

# Step.php

### Step methods reference

```php
// If no element provided, the step act like a modal
Step::make(string $element = null)

    // Define the title of your step
    // Mandatory
    ->title(string|Closure $title)
    
    // Define the description of your step
    // Also accept HTML
    // Mandatory
    ->description(string|Closure|HtmlString|View $description)
    
    // Define an icon next to your step title
    ->icon(string $icon)
    
    // Define the color of the title icon
    ->iconColor(string $color)
    
    // Step your step closeable or not
    // Default: true
    ->uncloseable(bool|Closure $uncloseable = true)
    
    //Simulate a click on a CSS selected element when you press the next button
    ->clickOnNext(string|Closure $selector)
    
    // Send a notification when you press the next button
    ->notifyOnNext(Notification $notification)
    
    //Redirect you to a custom url or a route() when you press the next button
    ->redirectOnNext(string $url, bool $newTab = false)
    
    // Dispatch an event like `$dispatch()` when you press the next button
    ->dispatchOnNext(string $name, ...$args)
```

# Highlights

Same as tour, use the correct trait !

- Use the correct trait to registers your highlights !

```php
<?php

namespace App\Filament\Pages;  
  
use JibayMcs\FilamentTour\Highlight\HasHighlight;  
  
class Dashboard extends FilamentDashboard {

    use HasHighlight;
    // ...  
  
    public function highlights(): array {    
	    return []; 
    }
}
```

- Create a simple highlight element !

```php
public function highlights(): array {

    return [
	 
        Highlight::make('.fi-header-heading')
            ->element('.fi-header-heading')
            ->title('Whoaw ! You highlighted the title of the page !')
            ->description('"Dashboard"'),
	
        Highlight::make('.fi-avatar')
            ->element('.fi-avatar')
            ->title("Pssst ! That's your avatar")
            ->icon('heroicon-o-user-circle')
            ->iconColor('primary'),
            	
    ];
}
````

___

# Highlight.php

### Highlight methods reference

```php
// Instanciate a highlight with a CSS select of the element where the icon button is next to
Highlight::make(string $parent)

    // Define the element to be highlighted
    ->element(string $element)

    // Set the title of your highlight
    ->title(string|Closure $title)

    // Set the description of your highlight
    ->description(string|Closure|HtmlString|View $description)

    // Define a custom icon for your highlight button
    // Default: heroicon-m-question-mark-circle
    ->icon(string $icon)

    // Define the color of the highlight icon button
    // Default: gray
    ->iconColor(string $color)

    // Define a color of your hightlight overlay for the dark and light theme of your filament panel
    ->colors(string $light, string $dark)

    // Set the position of your icon button around the parent
    // Default: top-left
    // Available: top-left, top-right, bottom-left, bottom-right
    ->position(string $position)
```

___

# Events

### Avalaible events:

- `filament-tour::open-highlight` **string** id  
  Open a specific highlight by its id.
  <br>
  <br>
- `filament-tour::open-tour` **string** id  
  Open a specific tour by its id.

___

Filament Tour, dispatch some event to show tours and highlights.
So you can trigger them from your own code.

Basically, if you want a custom button to trigger a tour or a highlight, you can do something like this:

```html
// ======== Highlights
// AlpineJS
<button x-on:click="Livewire.dispatch('filament-tour::open-highlight', 'title')">Show title highlight</button>

// Livewire
<button wire:click="$dispatch('filament-tour::open-highlight', 'title')">Show title highlight</button>

// ======== Tours
//AlpineJS
<button x-on:click="Livewire.dispatch('filament-tour::open-tour', 'title')">Show Dashboard tour</button>

// Livewire
<button wire:click="$dispatch('filament-tour::open-tour', 'dashboard')">Show Dashboard tour</button>
```

> **ℹ️**  
> Don't forget to prefix your event with `filament-tour::` to trigger the correct event.

# Development Tool

> [!IMPORTANT]  
> This tool is always disabled in production mode. `APP_ENV=production`

Filament Tour embed a simple tool to help you to develop your tours and highlights.

Let me show you how to use it !

### Enable the tool

To enable the tool, simply use `FilamentTourPlugin::make()->enableCssSelector()` in your plugin declaration.

### Keyboard shortcuts

<kbd>**Ctrl**</kbd>|<kbd>**Cmd**</kbd> + <kbd>**Space**</kbd> To open the tool.
<br>
<br>
<kbd>**Escape**</kbd> To exit the tool.
<br>
<br>
<kbd>**Ctrl**</kbd>|<kbd>**Cmd**</kbd> + <kbd>**C**</kbd> To copy the CSS Selector of the highlighted element.

[CSS Selector Tool Utilisation Preview](https://github.com/JibayMcs/filament-tour/assets/7621593/162db2a3-1f46-4493-ae0d-cffcb2f00462)

# Extra Resources

### DriverJS

- [DriverJS Website](https://driverjs.com)
- [DriverJS GitHub](https://github.com/kamranahmedse/driver.js) (Give some 🩵 to the author !)

The core of this plugin !  
Don't hesitate to check the documentation to learn more about the possibilities of this plugin.  
_I don't implemented all the features of DriverJS, at this time, but I'm working on it !_

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [JibayMcs](https://github.com/JibayMcs)
- [DriverJS](https://driverjs.com)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

