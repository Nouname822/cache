<?php

namespace Phantom\Cache\Adapter;

class RuntimeCache implements RuntimeInterface
{
    private string $namespace;
    private static array $data;

    public function __construct(string $namespace = 'default')
    {
        $this->namespace = $namespace;
    }

    public function set(string $key, mixed $value): void
    {
        static::$data[$this->namespace][$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return static::$data[$this->namespace][$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset(static::$data[$this->namespace][$key]);
    }

    public function del(string $key): void
    {
        unset(static::$data[$this->namespace][$key]);
    }

    public function clear(): void
    {
        static::$data[$this->namespace] = [];
    }

    public function all(): array
    {
        return static::$data[$this->namespace] ?? [];
    }
}
