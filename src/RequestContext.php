<?php
declare(strict_types=1);

namespace Falgun\Routing;

class RequestContext implements RequestContextInterface
{

    protected string $url;
    protected string $method;

    public function __construct(string $url, string $method)
    {
        $this->url = $url;
        $this->method = $method;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
