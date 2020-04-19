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

    public function testStaticGetRoute()
    {
        $router = new \Falgun\Routing\Router();
        $router->setBaseUrl(self::BASE_URL);
        $router->get('/test')->action('ControllerClassGET', 'MethodName')->middleware(['MiddlewareClass']);
        $router->post('/test')->action('ControllerClassPOST', 'MethodName')->middleware(['MiddlewareClass']);
        $router->delete('/test')->action('ControllerClassDELETE', 'MethodName')->middleware(['MiddlewareClass']);

        // GET
        $requestContext = new RequestContext(self::BASE_URL . 'test', 'GET');
        $matched = $router->dispatch($requestContext);

        $this->assertTrue($matched->getController() === 'ControllerClassGET');

        //POST
        $requestContext = new RequestContext(self::BASE_URL . 'test', 'POST');
        $matched = $router->dispatch($requestContext);

        $this->assertTrue($matched->getController() === 'ControllerClassPOST');

        //DELETE
        $requestContext = new RequestContext(self::BASE_URL . 'test', 'DELETE');
        $matched = $router->dispatch($requestContext);

        $this->assertTrue($matched->getController() === 'ControllerClassDELETE');
    }

    public function testGroupedRoute()
    {

        //List
        $router = new \Falgun\Routing\Router();
        $router->setBaseUrl(self::BASE_URL);
        $router->add(['GET'], '/list')->action('Controller', 'action');

        $router->group(['prefix' => '/admin'], function(Router $router) {
            $router->get('/list')->action('AdminController', 'AdminList');
        });

        $requestContext = new RequestContext(self::BASE_URL . 'admin/list', 'GET');
        $matched = $router->dispatch($requestContext);

        $this->assertTrue($matched->getController() === 'AdminController');
    }

    public function testRouteNotFound()
    {

        //List
        $router = new \Falgun\Routing\Router();
        $router->setBaseUrl(self::BASE_URL);
        $router->any('/list')->action('Controller', 'action');
        $router->any('/add')->action('Controller', 'action');
        $router->any('/edit')->action('Controller', 'action');

        $requestContext = new RequestContext(self::BASE_URL . 'admin/list', 'GET');
        try {
            $matched = $router->dispatch($requestContext);
        } catch (RouteNotFoundException $ex) {
            return $this->assertTrue(true);
        }

        $this->fail();
    }
}
