<?php

namespace Falgun\Routing\Collections;

use Falgun\Routing\RouteInterface;

interface RouteCollectionInterface extends \IteratorAggregate, \Countable
{

    public function set(RouteInterface $route): void;
}
