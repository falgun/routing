<?php
declare(strict_types=1);

namespace Falgun\Routing\Tests;

use Falgun\Routing\Router;
use PHPUnit\Framework\TestCase;
use Falgun\Routing\RequestContext;
use Falgun\Routing\RouteInterface;

class RegexRouteTest extends TestCase
{

    const SCHEME = 'http';
    const HOST = 'localhost';
    const URI = '/framework/public';

    public function testRegexRoutes()
    {
        $router = new Router(self::SCHEME . '://' . self::HOST . self::URI);
        $router->get('/test/{id:\d+}/{name:\S+}/{status:\d+}')
            ->action('ControllerClassGET', 'MethodName')
            ->middleware(['MiddlewareClass']);

        $router->post('/test/{id:int}/{name:word}/{email:string}')
            ->action('ControllerClassPOST', 'MethodName')
            ->middleware(['MiddlewareClass', 'AnotherMiddleware']);


        // Route 1
        $requestContext = new RequestContext('GET', self::SCHEME, self::HOST, self::URI . '/test/111/abc/999');
        $matched = $router->dispatch($requestContext);

        $this->assertTrue($matched instanceof RouteInterface);
        $this->assertTrue($matched->getController() === 'ControllerClassGET');
        $this->assertEquals(['id' => 111, 'name' => 'abc', 'status' => 999], $matched->getParameters());
        $this->assertEquals(['MiddlewareClass'], $matched->getMiddlewares());

        // Route 2
        $requestContext = new RequestContext('POST', self::SCHEME, self::HOST, self::URI . '/test/111/abc/email.com');
        $matched = $router->dispatch($requestContext);

        $this->assertTrue($matched instanceof RouteInterface);
        $this->assertTrue($matched->getController() === 'ControllerClassPOST');
        $this->assertEquals(['id' => 111, 'name' => 'abc', 'email' => 'email.com'], $matched->getParameters());
        $this->assertEquals(['MiddlewareClass', 'AnotherMiddleware'], $matched->getMiddlewares());

        $requestContext = new RequestContext('GET', self::SCHEME, self::HOST, self::URI . '/test/111/email');
        $this->expectException(\Falgun\Routing\RouteNotFoundException::class);
        $matched = $router->dispatch($requestContext);
    }
}
