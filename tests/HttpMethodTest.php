<?php
declare(strict_types=1);

namespace Falgun\Routing\Tests;

use Falgun\Routing\Route;
use Falgun\Routing\Router;
use PHPUnit\Framework\TestCase;
use Falgun\Routing\RequestContext;

class HttpMethodTest extends TestCase
{

    protected Router $router;

    public function setUp(): void
    {
        $this->buildRouter();
    }

    private function buildRouter(): Router
    {
        $router = new Router('http://website.com/');

        $router->add(['FAKE'], '/fake')->closure(fn() => 'fake');
        $router->any('/any')->closure(fn() => 'any');
        $router->get('/get')->closure(fn() => 'get');
        $router->post('/post')->closure(fn() => 'post');
        $router->delete('/delete')->closure(fn() => 'delete');
        $router->patch('/patch')->closure(fn() => 'patch');
        $router->put('/put')->closure(fn() => 'put');
        $router->head('/head')->closure(fn() => 'head');
        $router->add(['GET', 'POST'], '/getORpost')->closure(fn() => 'getORpost');

        return $this->router = $router;
    }

    private function execRouter(string $method, $uri): Route
    {
        $requestContext = RequestContext::fromUriParts($method, 'http', 'website.com', 80, $uri);

        return $this->router->dispatch($requestContext);
    }

    public function testGetMethod()
    {
        $route = $this->execRouter('GET', '/get');

        $this->assertEquals('get', $route->getClosure()());
    }

    public function testPostMethod()
    {
        $route = $this->execRouter('POST', '/post');

        $this->assertEquals('post', $route->getClosure()());
    }

    public function testDeleteMethod()
    {
        $route = $this->execRouter('DELETE', '/delete');

        $this->assertEquals('delete', $route->getClosure()());
    }

    public function testPatchMethod()
    {
        $route = $this->execRouter('PATCH', '/patch');

        $this->assertEquals('patch', $route->getClosure()());
    }

    public function testPutMethod()
    {
        $route = $this->execRouter('PUT', '/put');

        $this->assertEquals('put', $route->getClosure()());
    }

    public function testHeadMethod()
    {
        $route = $this->execRouter('HEAD', '/head');

        $this->assertEquals('head', $route->getClosure()());

        // head should work for get too
        $route = $this->execRouter('HEAD', '/get');

        $this->assertEquals('get', $route->getClosure()());
    }

    public function testAnyMethod()
    {
        $route = $this->execRouter('ANY', '/any');

        $this->assertEquals('any', $route->getClosure()());
    }

    public function testFakeMethod()
    {
        $route = $this->execRouter('FAKE', '/fake');

        $this->assertEquals('fake', $route->getClosure()());
    }

    public function testGetOrPostMethod()
    {
        $route = $this->execRouter('GET', '/getORpost');

        $this->assertEquals('getORpost', $route->getClosure()());

        $route = $this->execRouter('POST', '/getORpost');

        $this->assertEquals('getORpost', $route->getClosure()());
    }

    public function testMethodNotMatched()
    {
        $this->expectException(\Falgun\Routing\RouteNotFoundException::class);
        $route = $this->execRouter('DELETE', '/getORpost');
    }
}
