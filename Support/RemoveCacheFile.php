<?php

namespace Nurymbet\Phantom\Cache\Support;

use Exception;
use Nurymbet\Phantom\Cache\Adapter\FileManager;
use Nurymbet\Phantom\Cache\Adapter\FolderManager;
use Nurymbet\Phantom\Cache\Exception\CacheException;
use Nurymbet\Phantom\Cache\Setting;

class RemoveCacheFile
{
    private array $config;

    public function __construct(
        private string $namespace,
        private string $key,
    ) {
        $this->config = Setting::get();
    }

    public function main(): bool
    {
        try {
            if (!(new CheckCacheFile($this->namespace, $this->key))->main()) {
                return true;
            }

            $safeNamespace = FolderManager::sanitizeFolderName($this->namespace);
            $safeKey = FolderManager::sanitizeFolderName($this->key);

            $fileManager = new FileManager(rtrim($this->config['file']['path'], '/') . '/' . $safeNamespace);

            return $fileManager->del($safeKey . '.' . $this->config['file']['extension']);
        } catch (Exception $e) {
            throw new CacheException($e->getMessage(), $e->getCode(), $e);
            return false;
        }
    }
}
