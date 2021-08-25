# simple-module

laravel simple module  
now version : v0.1.8

### 0. check

Added the following to composer.json

```
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Module\\": "module/", <- added
            ...
        }
    },
    ...
```

### 1. publish

```
php artisan vendor:publish --provider="Takemo101\SimpleModule\SimpleModuleServiceProvider"
```

### 2. create module

```
php artisan simple-module:create ModuleName

or

php artisan simple-module:create ModuleName --namespace=SubModuleNamespace
```

### 3. module install

```
php artisan simple-module:install

or

php artisan simple-module:install --module=ModuleName
```

### 4. module uninstall

```
php artisan simple-module:uninstall

or

php artisan simple-module:uninstall --module=ModuleName
```
