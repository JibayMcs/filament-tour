# Changelog

All notable changes to `filament-tour` will be documented in this file.

## What's new ? - 2023-09-14

- Fixed error on parsing URL parameters from the tour routing system

**Full Changelog**: https://github.com/JibayMcs/filament-tour/compare/v3.1.0.1...v3.1.0.2

## The JSON Update - 2023-09-06

### The JSON Update <sup>ᴠ3.1.0.1</sup>

If creating your guided tours in PHP bores you or takes up too much space, play with JSON!

You can now load your tours directly using a JSON file from a URL or your Storage!

- Finished setup for multiple tours registration, now "goto" a next tour on finished the first one

## v3.1.0.0 - 2023-09-05

### The First Release !

#### Development Tool :eyes:

[Check it here !](https://github.com/JibayMcs/filament-tour/blob/3.x/README.md#development-tool)

#### Tour

- Added to make all child steps uncloseable   
     
  - `function uncloseable(bool|Closure $uncloseable = true)`   
  
- Added to disable all steps events   
     
  - `function disableEvents(bool|Closure $disableEvents = true)`   
  
- Added to ignore routes check to launch Tour   
     
  - `function ignoreRoutes(bool|Closure $ignoreRoutes = true)`   
  

## 1.0.0 - 202X-XX-XX

- initial release
