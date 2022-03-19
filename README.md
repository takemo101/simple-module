# The Simple Module For Laravel

[![Testing](https://github.com/takemo101/simple-module/actions/workflows/testing.yml/badge.svg)](https://github.com/takemo101/simple-module/actions/workflows/testing.yml)
[![PHPStan](https://github.com/takemo101/simple-module/actions/workflows/phpstan.yml/badge.svg)](https://github.com/takemo101/simple-module/actions/workflows/phpstan.yml)
[![Validate Composer](https://github.com/takemo101/simple-module/actions/workflows/composer.yml/badge.svg)](https://github.com/takemo101/simple-module/actions/workflows/composer.yml)

A very simple modular system for Laravel.  
Enjoy!  

## Installation
Execute the following composer command.

```
composer require takemo101/simple-module
```

## Publish the config
Publish the config with the following artisan command.
```
php artisan vendor:publish --tag="simple-module"
```

## About　composer.json
Create a'module' directory in the directory where composer.json is located.  
Then set the path of the directory you added to composer.json.  
```json
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Module\\": "module/", <- added
            ...
        }
    },
    ...
```

## How to use
You can execute the command as below.

### 1. Create a module
Create a module file in the module directory
```
php artisan simple-module:create ModuleName

or

php artisan simple-module:create ModuleName --namespace=OtherModuleNamespace
```

### 2. module install
Execute the process of the install method of Module.php of the created module file.
```
php artisan simple-module:install

or

php artisan simple-module:install --module=ModuleName
```

### 3. module uninstall
Execute the process of uninstall method of Module.php of the created module file.
```
php artisan simple-module:uninstall

or

php artisan simple-module:uninstall --module=ModuleName
```

### 4. module update
Only update the dependent packages of the created module file.
```
php artisan simple-module:update

or

php artisan simple-module:update --module=ModuleName
```

## How to set the module file
The following is a setting example of Module.php.
```php
<?php

namespace Other\Sync;

use Takemo101\SimpleModule\Support\ {
    InstallerInterface,
    ServiceProvider,
};

/**
 * Module files are Laravel's service provider, so you can use them in the same way.
 */
class Module extends ServiceProvider implements InstallerInterface
{
    public function register()
    {
        //
    }

    public function boot()
    {
        //
    }

    /**
     * module install process
     *
     * @return void
     */
    public function install()
    {
        // Write the process when installing the module.
    }

    /**
     * module uninstall process
     *
     * @return void
     */
    public function uninstall()
    {
        // Write the process when uninstalling the module.
    }

    /**
     * install packages
     *
     * Set the package string to the key of the associative array.
     * For the value of the associative array, set whether to uninstall or not with boolean type.
     *
     * @return boolean[]
     */
    public function packages(): array
    {
        return [
            'bensampo/laravel-enum' => true, // It is deleted at the same time as uninstalling
            'jeroennoten/laravel-adminlte' => false, // Not deleted even if uninstalled
        ];
    }
}
```
