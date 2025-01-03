<?php declare(strict_types=1);

namespace Az\Route;

class Route
{
    private $handler;
    private array $methods = [];
    private array $tokens = [];
    private ?string $host = null;
    private array $parameters = [];
    private array $filters = [];
    private ?bool $ajax = null;

    public function __construct($handler, $methods)
    {
        $this->handler = $handler;
        $this->methods($methods);
    }

    public function methods($methods)
    {
        if (is_string($methods)) {
            $methods = [$methods];
        }

        $this->methods = array_map(fn ($v) => strtoupper($v), $methods);
        return $this;
    }

    public function tokens(array $tokens): self
    {
        foreach ($tokens as $key => $token) {
            $this->tokens[$key] = $token;
        }

        return $this;
    }

    public function host($host): self
    {
        $this->host = $host;
        return $this;
    }

    public function filter(callable|array $filter): self
    {
        $this->filters[] = $filter;
        return $this;
    }

    public function ajax(?bool $ajax = null): self
    {
        $this->ajax = $ajax;
        return $this;
    }

    public function isAjax(): ?bool
    {
        return $this->ajax;
    }

    public function getTokens(): array
    {
        return $this->tokens;
    }

    public function setParameters($params)
    {
        $this->parameters = array_replace($this->parameters, array_filter($params));
        unset($this->parameters['action']);
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getHandler(): mixed
    {
        return $this->handler;
    }

    public function getMethods()
    {
        return $this->methods;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getFilters()
    {
        return $this->filters;
    }
}
