# Routing

Simple Routing Library.

## Install
 *Please note that PHP 7.4 or higher is required.*

Via Composer

``` bash
$ composer require falgunphp/routing
```

## Usage
``` php
<?php
use Falgun\Routing\Router;
use Falgun\Routing\RequestContext;

$router = new Router('http://localhost/');

$router->any('/')->action(HomeController::class, 'index');
$router->get('/test')->action(TestController::class, 'index');


// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// create request context
$requestContext = new RequestContext($uri, $httpMethod);

/* @var $route RouteInterface */
$route = $this->router->dispatch($requestContext);
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
