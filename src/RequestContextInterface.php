<?php

namespace Falgun\Routing;

interface RequestContextInterface
{

    public function getMethod(): string;

    public function getScheme(): string;

    public function getHost(): string;

    public function getUri(): string;

    public function getFullUrl(): string;
}
