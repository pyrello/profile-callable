# Callable Profiler
A simple plugin for Artisan (Laravel) to display basic profiling information about a callable method or function.

## Installation
*Coming soon*

## Example Usage
    php artisan profile:callable Vendor\MyClass::myCallableMethod --arguments='comma, separated, list'

## Example Output
    Profiling [Vendor\MyClass::myCallableMethod::myCallableMethod]:
    Time taken: 1.13009905815 seconds
    Memory used: 1.79667663574 MiB
    Operations: 1237
