<?php
declare(strict_types=1);

namespace Falgun\Routing\Collections;

use Falgun\Routing\RouteInterface;

class RegexRouteCollection implements RouteCollectionInterface
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
        $this->routes[] = $route;
    }
}
