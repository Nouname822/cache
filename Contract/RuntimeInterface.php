<?php

namespace Nurymbet\Phantom\Cache\Adapter;

interface RuntimeInterface
{
    public function __construct(string $namespace = 'default');
    public function set(string $key, mixed $value): void;
    public function get(string $key, mixed $default = null): mixed;
    public function has(string $key): bool;
    public function del(string $key): void;
    public function clear(): void;
    public function all(): array;
}
