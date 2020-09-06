<?php
declare(strict_types=1);

namespace Falgun\Routing\Collections;

use Falgun\Routing\RouteInterface;

class StaticRouteCollection implements RouteCollectionInterface
{

    /**
     *
     * @var RouteInterface[][]
     */
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
        $url = $route->getRouteUrl();

        $this->routes[$url][] = $route;
    }

    public function get(string $key): array
    {
        if ($this->has($key)) {
            return $this->routes[$key];
        }

        throw new \InvalidArgumentException('No route with key: "' . $key . '" found!');
    }

    public function has(string $key): bool
    {
        return isset($this->routes[$key]);
    }
}
