<?php

namespace Falgun\Routing;

use Closure;

interface RouterInterface
{

    public function build(array $httpMethods, string $routeUrl): RouteInterface;

    public function group(array $options, Closure $callback): void;

    public function setBaseUrl(string $url = ''): void;

    public function dispatch(RequestContextInterface $requestContext): RouteInterface;

    public function add(array $httpMethods, string $route): RouteInterface;

    public function any(string $route): RouteInterface;

    public function get(string $route): RouteInterface;

    public function post(string $route): RouteInterface;

    public function put(string $route): RouteInterface;

    public function delete(string $route): RouteInterface;

    public function patch(string $route): RouteInterface;

    public function head(string $route): RouteInterface;

    public function options(string $route): RouteInterface;
}
