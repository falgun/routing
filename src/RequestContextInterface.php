<?php

namespace Falgun\Routing;

interface RequestContextInterface
{

    public function getUrl(): string;

    public function getMethod(): string;
}
