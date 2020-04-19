<?php
declare(strict_types=1);

namespace Falgun\Routing\Collections;

use Falgun\Routing\RouteInterface;

class StaticRouteCollection implements RouteCollectionInterface
{

    protected array $routes;

    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    public function count(): int
    {
        return \count($this->routes);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->routes);
    }

    public function set(RouteInterface $route): void
    {
        $this->routes[$route->getRouteUrl()][] = $route;
    }

    public function get(string $key): array
    {
        if ($this->has($key) === false) {
            throw new \Exception('No route with key: "' . $key . '" found!');
        }

        return $this->routes[$key];
    }

    public function has(string $key): bool
    {
        return isset($this->routes[$key]);
    }

    public function remove(string $key): void
    {
        unset($this->routes[$key]);
    }
}
