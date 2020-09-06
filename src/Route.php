<?php
declare(strict_types=1);

namespace Falgun\Routing;

use Closure;

class Route implements RouteInterface
{

    protected string $routeUrl;
    protected array $httpMethods;
    protected array $middlewares;
    protected string $controller;
    protected string $method;
    protected ?Closure $closure;
    protected array $parameters;

    public function __construct(array $httpMethods, string $routeUrl)
    {
        $this->httpMethods = $httpMethods;
        $this->routeUrl = $routeUrl;
        $this->controller = '';
        $this->method = '';
        $this->middlewares = [];
        $this->parameters = [];
        $this->closure = null;
    }

    public function action(string $controller, string $method): self
    {
        $this->controller = $controller;
        $this->method = $method;

        return $this;
    }

    public function closure(Closure $closure): self
    {
        $this->closure = $closure;

        return $this;
    }

    public function middleware(array $middlewares, bool $overWrite = false): self
    {
        if ($overWrite === false && !empty($this->middlewares)) {
            $this->middlewares = \array_merge($this->middlewares, $middlewares);
        } else {
            $this->middlewares = $middlewares;
        }


        return $this;
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setParameter(string $key, $value): void
    {
        $this->parameters[$key] = $value;
    }

    public function getRouteUrl(): string
    {
        return $this->routeUrl;
    }

    public function getHttpMethods(): array
    {
        return $this->httpMethods;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getClosure(): ?Closure
    {
        return $this->closure;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
