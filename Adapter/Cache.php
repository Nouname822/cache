<?php

namespace Nurymbet\Phantom\Cache\Adapter;

use Nurymbet\Phantom\Cache\Setting;
use Nurymbet\Phantom\Cache\Support\CheckCacheFile;
use Nurymbet\Phantom\Cache\Support\CreateCacheFile;
use Nurymbet\Phantom\Cache\Support\ReadCacheFile;
use Nurymbet\Phantom\Cache\Support\RemoveCacheFile;

class Cache implements CacheInterface
{
    private string $namespace = 'default';

    public function __construct(string $namespace = 'default')
    {
        $this->namespace = $namespace;
    }

    public function has(string $key): bool
    {
        return (new CheckCacheFile($this->namespace, $key))->main();
    }

    public function set(string $key, mixed $value): bool
    {
        return (new CreateCacheFile($this->namespace, $key, $value))->main();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return (new ReadCacheFile($this->namespace, $key, $default))->main();
    }

    public function del(string $key): bool
    {
        return (new RemoveCacheFile($this->namespace, $key))->main();
    }

    public function clear(bool $all = false): bool
    {
        $path = Setting::get()['file']['path'];
        $folderManager = new FolderManager($path);
        return $folderManager->del($all ? '' : FolderManager::sanitizeFolderName($this->namespace));
    }
}
