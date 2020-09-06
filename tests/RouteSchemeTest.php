<?php
declare(strict_types=1);

namespace Falgun\Routing\Tests;

use Falgun\Routing\Route;
use Falgun\Routing\Router;
use PHPUnit\Framework\TestCase;
use Falgun\Routing\RequestContext;

class RouteSchemeTest extends TestCase
{

    public function testMultiSchemeFail()
    {
        $router = new Router('http://website.com');

        $router->any('/test');

        // success
        $context = new RequestContext('GET', 'http', 'website.com', '/test');
        $route = $router->dispatch($context);
        $this->assertTrue($route instanceof Route);

        // fail
        $context = new RequestContext('GET', 'https', 'website.com', '/test');

        $this->expectException(\Falgun\Routing\RouteNotFoundException::class);
        $router->dispatch($context);
    }
}
