<?php
declare(strict_types=1);

namespace Falgun\Routing\Builders;

use Falgun\Routing\Route;
use Falgun\Routing\RouteInterface;

class RouteBuilder
{

    protected string $baseUrl;
    protected string $prefix;
    protected array $middlewares;

    public function __construct(string $baseUrl)
    {
        $this->setBaseUrl($baseUrl);
        $this->prefix = '';
        $this->middlewares = [];
    }

    public function buildAsRegex(array $httpMethods, string $routeUrl): RouteInterface
    {
        $routeUrl = $this->prepareRouteUrl($routeUrl);
        $routeUrl = $this->regularExpression($routeUrl);

        $route = new Route($httpMethods, $routeUrl);

        if (!empty($this->middlewares)) {
            $route->middleware($this->middlewares);
        }

        return $route;
    }

    public function buildAsStatic(array $httpMethods, string $routeUrl): RouteInterface
    {
        $routeUrl = $this->prepareRouteUrl($routeUrl);

        $route = new Route($httpMethods, $routeUrl);

        if (!empty($this->middlewares)) {
            $route->middleware($this->middlewares);
        }

        return $route;
    }

    protected function prepareRouteUrl(string $routeUrl): string
    {
        $fullRouteUrl = $this->baseUrl . $this->prefix . $routeUrl;

        return $this->cleanUrl($fullRouteUrl);
    }

    protected function regularExpression(string $routeUrl): string
    {
        $routeUrl = $this->easyRegEx($routeUrl);
        $routeUrl = \preg_replace("/\{([a-zA-Z0-9\_]+):([^\}]+)\}/", "(?P<$1>$2)", $routeUrl);
        $routeUrl = '~^' . $routeUrl . '$~';

        return $routeUrl;
    }

    protected function easyRegEx(string $routeUrl): string
    {
        $easyRegExArray = array(':int}', ':word}', ':string}');
        $regExArray = array(':[\d^/]+}', ':[\w^/]}', ':[\S^/]+}');

        return \str_replace($easyRegExArray, $regExArray, $routeUrl);
    }

    protected function cleanUrl(string $url): string
    {
        $url = \ltrim($url);

        return \rtrim($url, " \t\n\r\0\x0B" . "/");
    }

    public function setBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = $this->cleanUrl($baseUrl);
    }

    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function setMiddlewares(array $middlewares): void
    {
        $this->middlewares = $middlewares;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function removePrefix(): void
    {
        $this->prefix = '';
    }

    public function mutateFromArray(array $options): void
    {
        foreach ($options as $property => $value) {
            if (\property_exists($this, $property)) {
                $this->{$property} = $value;
            } else {
                throw new \Exception('propery ' . $property . ' does not exist in RouteBuilder');
            }
        }
    }
}
