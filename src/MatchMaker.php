<?php
declare(strict_types=1);

namespace Falgun\Routing;

use Falgun\Routing\Collections\RegexRouteCollection;
use Falgun\Routing\Collections\StaticRouteCollection;

class MatchMaker
{

    protected $staticRoutes;
    protected $regexRoutes;

    public function __construct(StaticRouteCollection $staticRoutes, RegexRouteCollection $regexRoutes)
    {
        $this->staticRoutes = $staticRoutes;
        $this->regexRoutes = $regexRoutes;
    }

    public function matchWith(RequestContextInterface $requestContext)
    {
        $staticRoute = $this->matchWithStaticRoutes($requestContext);

        if ($staticRoute !== false) {
            // matched with a static route
            return $staticRoute;
        }

        // Loop through all regex routes
        foreach ($this->regexRoutes as $route) {
            if ($this->matchWithRegexRoute($route, $requestContext)) {
                return $route;
            }
        }

        return false;
    }

    protected function matchWithRegexRoute(RouteInterface $route, RequestContextInterface $requestContext): bool
    {
        if ($this->isMethodSupported($route, $requestContext->getMethod()) === false) {
            return false;
        }

        if (\preg_match($route->getRouteUrl(), $this->getUrl($requestContext), $parameters)) {
            $route->setParameters($this->prepareRouteParameters($parameters));

            return true;
        }

        return false;
    }

    protected function prepareRouteParameters(array $matches): array
    {
        return \array_filter($matches, function ($key) {
            return \is_string($key);
        }, \ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param StaticRouteCollection $routes
     * @return RouteInterface|boolean
     */
    protected function matchWithStaticRoutes(RequestContextInterface $requestContext)
    {
        if ($this->staticRoutes->has($this->getUrl($requestContext)) === false) {
            return false;
        }

        $matchedRoutes = $this->staticRoutes->get($this->getUrl($requestContext));

        foreach ($matchedRoutes as $route) {
            if ($this->isMethodSupported($route, $requestContext->getMethod()) === true) {
                return $route;
            }
        }

        return false;
    }

    protected function isMethodSupported(RouteInterface $route, string $method): bool
    {
        return (empty($route->getHttpMethods()) || \in_array($method, $route->getHttpMethods(), true));
    }

    protected function getUrl(RequestContextInterface $requestContext): string
    {
        $url = \ltrim($requestContext->getUrl());

        return \rtrim($url, " \t\n\r\0\x0B" . "/");
    }
}
