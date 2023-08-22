# Bring the power of DriverJs to your Filament panels and start a tour !

With the power of [DriverJS](https://driverjs.com) bring to your users an elegant way to discover your panels !

## Installation

You can install this filament plugin via composer:

```bash
composer require jibaymcs/filament-tour:"^3.x"
```

You can publish the config file with:

```
bash php artisan vendor:publish --tag="filament-tour-config"
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
// Instanciate a tour, an provide an id, to trigger it later
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
	->onNextClick(string|Closure $selector)

	// Send a notification when you press the next button
	->onNextNotify(Notification $notification)

	//Redirect you to a custom url when you press the next button
	->onNextRedirect(string $url, bool $newTab = false)

	// Dispatch an event like `$dispatch()` when you press the next button
	->onNextDispatch(string $name, ...$args)
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
  
	public function highlights(): array    {    
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

