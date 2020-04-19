<?php

namespace Falgun\Routing;

use Closure;

interface RouteInterface
{

    public function action(string $controller, string $method): self;

    public function callback(Closure $closure): self;

    public function middleware(array $middlewares, bool $overWrite = false): self;

    public function setParameters(array $parameters): void;

    public function setParameter(string $key, $value): void;

    public function getRouteUrl(): string;

    public function getHttpMethods(): array;

    public function getMiddlewares(): array;

    public function getController(): string;

    public function getMethod(): string;

    public function getClosure(): ?Closure;

    public function getParameters(): array;
}
