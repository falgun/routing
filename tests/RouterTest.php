<?php
declare(strict_types=1);

namespace Falgun\Routing\Tests;

use Falgun\Routing\Route;
use Falgun\Routing\Router;
use PHPUnit\Framework\TestCase;
use Falgun\Routing\RequestContext;
use Falgun\Routing\RouteNotFoundException;

class RouterTest extends TestCase
{

    const BASE_URL = 'http://localhost/framework/public/';
    const SCHEME = 'http';
    const HOST = 'localhost';
    const PORT = 80;
    const URI = '/framework/public';

    public function testStaticGetRoute()
    {
        $router = new \Falgun\Routing\Router(self::BASE_URL);
        $router->get('/test')->action('ControllerClassGET', 'MethodName')->middleware(['MiddlewareClass']);
        $router->post('/test')->action('ControllerClassPOST', 'MethodName')->middleware(['MiddlewareClass']);
        $router->delete('/test')->action('ControllerClassDELETE', 'MethodName')->middleware(['MiddlewareClass']);

        // GET
        $requestContext = RequestContext::fromUriParts('GET', self::SCHEME, self::HOST, self::PORT, self::URI . '/test');
        $matched = $router->dispatch($requestContext);

        $this->assertTrue($matched->getController() === 'ControllerClassGET');

        //POST
        $requestContext = RequestContext::fromUriParts('POST', self::SCHEME, self::HOST, self::PORT, self::URI . '/test');
        $matched = $router->dispatch($requestContext);

        $this->assertTrue($matched->getController() === 'ControllerClassPOST');

        //DELETE
        $requestContext = RequestContext::fromUriParts('DELETE', self::SCHEME, self::HOST, self::PORT, self::URI . '/test');
        $matched = $router->dispatch($requestContext);

        $this->assertTrue($matched->getController() === 'ControllerClassDELETE');
    }

    public function testRouteUrlWithoutSlash()
    {
        $router = new \Falgun\Routing\Router(self::BASE_URL);
        $router->put('test');

        $requestContext = RequestContext::fromUriParts('PUT', self::SCHEME, self::HOST, self::PORT, self::URI . '/test');

        $this->expectException(RouteNotFoundException::class);
        $matched = $router->dispatch($requestContext);
    }

    public function testClosureRoute()
    {
        $router = new \Falgun\Routing\Router(self::BASE_URL);
        $router->get('/test')->closure(function() {
            
        });

        // GET
        $requestContext = RequestContext::fromUriParts('GET', self::SCHEME, self::HOST, self::PORT, self::URI . '/test');
        $matched = $router->dispatch($requestContext);

        $this->assertTrue($matched->getClosure() instanceof \Closure);
    }

    public function testGroupedRoute()
    {

        //List
        $router = new \Falgun\Routing\Router(self::BASE_URL);
        $router->add(['GET'], '/list')->action('Controller', 'action');

        $router->group(['prefix' => '/admin'], function(Router $router) {
            $router->get('/list')->action('AdminController', 'AdminList');
        });

        $requestContext = RequestContext::fromUriParts('GET', self::SCHEME, self::HOST, self::PORT, self::URI . '/admin/list');
        $matched = $router->dispatch($requestContext);

        $this->assertTrue($matched->getController() === 'AdminController');
    }

    public function testRouteNotFound()
    {
        //List
        $router = new \Falgun\Routing\Router(self::BASE_URL);
        $router->any('/list')->action('Controller', 'action');
        $router->any('/add')->action('Controller', 'action');
        $router->any('/edit')->action('Controller', 'action');

        $requestContext = RequestContext::fromUriParts('GET', self::SCHEME, self::HOST, self::PORT, self::URI . '/admin/list');

        $this->expectException(RouteNotFoundException::class);
        $matched = $router->dispatch($requestContext);
    }
}
