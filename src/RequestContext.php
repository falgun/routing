<?php
declare(strict_types=1);

namespace Falgun\Routing;

class RequestContext implements RequestContextInterface
{

    protected string $method;
    protected string $scheme;
    protected string $host;
    protected string $uri;

    public function __construct(string $method, string $scheme, string $host, string $uri)
    {
        $this->method = $method;
        $this->scheme = $scheme;
        $this->host = $host;
        $this->uri = $uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getFullUrl(): string
    {
        return $this->scheme . '://' . $this->host . $this->uri;
    }
}
