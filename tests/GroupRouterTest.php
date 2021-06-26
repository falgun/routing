<?php
declare(strict_types=1);

namespace Falgun\Routing\Tests;

use Falgun\Routing\Router;
use PHPUnit\Framework\TestCase;
use Falgun\Routing\RequestContext;
use Falgun\Routing\RouteInterface;

class GroupRouterTest extends TestCase
{

    public function testGroupedBaseDomain()
    {
        $router = new Router('http://website.com/');

        $router->get('/list')->action('Controller', 'website');

        $router->group(['baseUrl' => 'http://anothersite.com'],
            function(Router $router) {

            $router->get('/list')->action('Controller', 'anothersite');
        });

        $router->group(['baseUrl' => 'http://yetanothersite.com'],
            function(Router $router) {

            $router->get('/list')->action('Controller', 'yetanothersite');
        });

        $requestContext = RequestContext::fromUriParts('GET', 'http', 'website.com', 80, '/list');

        $matched = $router->dispatch($requestContext);

        $this->assertEquals('website', $matched->getMethod());

        $requestContext = RequestContext::fromUriParts('GET', 'http', 'anothersite.com', 80, '/list');

        $matched = $router->dispatch($requestContext);

        $this->assertEquals('anothersite', $matched->getMethod());

        $requestContext = RequestContext::fromUriParts('GET', 'http', 'yetanothersite.com', 80, '/list');

        $matched = $router->dispatch($requestContext);

        $this->assertEquals('yetanothersite', $matched->getMethod());
    }

    public function testInvalidGroupParam()
    {
        $router = new Router('http://website.com/');

        $this->expectException(\InvalidArgumentException::class);
        $router->group(['invalid' => 'data'], function(Router $router) {
            
        });
    }
}
