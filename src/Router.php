<?php
declare(strict_types=1);

namespace Falgun\Routing;

use Closure;
use Falgun\Routing\Builders\RouteBuilder;
use Falgun\Routing\Collections\RegexRouteCollection;
use Falgun\Routing\Collections\StaticRouteCollection;

class Router implements RouterInterface
{

    protected RouteBuilder $routeBuilder;
    protected RegexRouteCollection $regexRoutes;
    protected StaticRouteCollection $staticRoutes;

    final public function __construct(string $baseUrl)
    {
        $this->regexRoutes = new RegexRouteCollection();
        $this->staticRoutes = new StaticRouteCollection();
        $this->routeBuilder = new RouteBuilder($baseUrl);
    }

    protected function isDynamicRouteUrl(string $routeUrl): bool
    {
        return \strpos($routeUrl, '{') !== false;
    }

    protected function build(array $httpMethods, string $routeUrl): RouteInterface
    {
        if ($this->isDynamicRouteUrl($routeUrl) === true) {
            $route = $this->routeBuilder->buildAsRegex($httpMethods, $routeUrl);

            $this->regexRoutes->set($route);
        } else {
            $route = $this->routeBuilder->buildAsStatic($httpMethods, $routeUrl);

            $this->staticRoutes->set($route);
        }

        return $route;
    }

    /**
     * @param array $options
     * @param Closure(RouterInterface): void $closure
     * @return void
     */
    public function group(array $options, Closure $closure): void
    {
        $backup = $this->routeBuilder;

        $newRouteBuilder = clone $this->routeBuilder;
        $newRouteBuilder->mutateFromArray($options);

        // set new builder as current one
        $this->routeBuilder = $newRouteBuilder;

        // execute the closure
        $closure($this);

        // Restore Old builder from backup
        $this->routeBuilder = $backup;
    }

    public function dispatch(RequestContextInterface $requestContext): RouteInterface
    {
        $matchmaker = new MatchMaker($this->staticRoutes, $this->regexRoutes);

        $matchedRoute = $matchmaker->matchWith($requestContext);

        return $matchedRoute;
    }

    public function setBaseUrl(string $baseUrl): void
    {
        $this->routeBuilder->setBaseUrl($baseUrl);
    }

    public function add(array $httpMethods, string $route): RouteInterface
    {
        return $this->build($httpMethods, $route);
    }

    public function any(string $route): RouteInterface
    {
        return $this->build([], $route);
    }

    public function get(string $route): RouteInterface
    {
        return $this->build(['GET', 'HEAD'], $route);
    }

    public function post(string $route): RouteInterface
    {
        return $this->build(['POST'], $route);
    }

    public function put(string $route): RouteInterface
    {
        return $this->build(['PUT'], $route);
    }

    public function delete(string $route): RouteInterface
    {
        return $this->build(['DELETE'], $route);
    }

    public function patch(string $route): RouteInterface
    {
        return $this->build(['PATCH'], $route);
    }

    public function head(string $route): RouteInterface
    {
        return $this->build(['HEAD'], $route);
    }

    public function options(string $route): RouteInterface
    {
        return $this->build(['OPTIONS'], $route);
    }
}
