# Bring the power of DriverJs to your Filament panels and start a tour !

With the power of [DriverJS](https://driverjs.io) bring to your users an elegant way to discover your panels !

## Installation

You can install this filament plugin via composer:

```bash
composer require jibaymcs/filament-tour
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

## Start a tour !

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
			 ),
	 ];
	 
}  
```  

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [JibayMcs](https://github.com/JibayMcs)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
