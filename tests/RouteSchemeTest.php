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
        $context = RequestContext::fromUriParts('GET', 'http', 'website.com', 80, '/test');
        $route = $router->dispatch($context);
        $this->assertTrue($route instanceof Route);

        // fail
        $context = RequestContext::fromUriParts('GET', 'https', 'website.com', 80, '/test');

        $this->expectException(\Falgun\Routing\RouteNotFoundException::class);
        $router->dispatch($context);
    }
}
