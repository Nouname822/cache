<?php

namespace Nurymbet\Phantom\Cache\Contract;

interface RedisInterface
{
    public function __construct(string $namespace = 'default');

    public function del(string|array $key): int;
    public function has(string|array $key): int;
    public function get(string $key, mixed $default = null): mixed;
    public function set(string $key, mixed $value): bool;
    public function clearCurrentDatabase(): bool;
    public function clearAllDatabases(): bool;
}
