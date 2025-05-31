<?php

namespace Nurymbet\Phantom\Cache\Adapter;

use Exception;
use Nurymbet\Phantom\Cache\Exception\RedisException;
use Nurymbet\Phantom\Cache\Setting;
use Predis\Client;

class Redis implements RedisInterface
{
    private string $namespace;
    private Client $client;

    public function __construct(string $namespace = 'default')
    {
        $this->namespace = $namespace;
        $this->client = new Client(Setting::get()['redis']);
    }

    public function set(string $key, mixed $value): bool
    {
        try {
            $this->client->set($this->namespace . ':' . $key, serialize($value));
            return true;
        } catch (Exception $e) {
            throw new RedisException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        try {
            $result = $this->client->get($this->namespace . ':' . $key);
            return isset($result) ? unserialize($result) : $default;
        } catch (Exception $e) {
            throw new RedisException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function has(string|array $keys): int
    {
        try {
            $key = is_string($keys) ? $this->namespace . ':' . $keys : $keys;
            return $this->client->exists($key);
        } catch (Exception $e) {
            throw new RedisException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function del(string|array $keys): int
    {
        try {
            $key = is_string($keys) ? $this->namespace . ':' . $keys : $keys;
            return $this->client->del($key);
        } catch (Exception $e) {
            throw new RedisException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function clearCurrentDatabase(): bool
    {
        try {
            return $this->client->flushdb() === 'OK';
        } catch (Exception $e) {
            throw new RedisException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function clearAllDatabases(): bool
    {
        try {
            return $this->client->flushall() === 'OK';
        } catch (Exception $e) {
            throw new RedisException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
