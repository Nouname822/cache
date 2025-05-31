<?php

namespace Phantom\Cache\Adapter;

interface CacheInterface
{
    public function __construct(string $namespace = 'default');

    public function del(string $key): bool;
    public function has(string $key): bool;
    public function get(string $key, mixed $default = null): mixed;
    public function set(string $key, mixed $value): bool;
}
