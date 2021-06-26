<?php
declare(strict_types=1);

namespace Falgun\Routing;

final class RequestContext implements RequestContextInterface
{

    private string $method;
    private string $scheme;
    private string $host;
    private int $port;
    private string $uri;

    private function __construct(string $method, string $scheme, string $host, int $port, string $uri)
    {
        $this->method = $method;
        $this->scheme = $scheme;
        $this->host = $host;
        $this->port = $port;
        $this->uri = $uri;
    }

    public static function fromUriParts(string $method, string $scheme, string $host, int $port, string $uri): RequestContextInterface
    {
        return new RequestContext($method, $scheme, $host, $port, $uri);
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

    public function getPort(): int
    {
        return $this->port;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getFullUrl(): string
    {
        return $this->scheme . '://' . $this->host . $this->getPortAsStringIfNotDefault() . $this->uri;
    }

    private function getPortAsStringIfNotDefault(): string
    {
        if ($this->port !== 80 && $this->port !== 443) {
            // current port is not default http,https port
            return ':' . $this->port;
        }
        //no need to show default port
        return '';
    }
}
