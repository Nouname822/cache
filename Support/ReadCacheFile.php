<?php

namespace Nurymbet\Phantom\Cache\Support;

use Exception;
use Nurymbet\Phantom\Cache\Adapter\FileManager;
use Nurymbet\Phantom\Cache\Adapter\FolderManager;
use Nurymbet\Phantom\Cache\Exception\CacheException;
use Nurymbet\Phantom\Cache\Setting;

class ReadCacheFile
{
    private array $config;

    public function __construct(
        private string $namespace,
        private string $key,
        private mixed $default
    ) {
        $this->config = Setting::get();
    }

    public function main(): mixed
    {
        try {
            if (!(new CheckCacheFile($this->namespace, $this->key))->main()) {
                return $this->default;
            }

            $safeNamespace = FolderManager::sanitizeFolderName($this->namespace);
            $safeKey = FolderManager::sanitizeFolderName($this->key);

            $fileManager = new FileManager(rtrim($this->config['file']['path'], '/') . '/' . $safeNamespace);

            return json_decode($fileManager->read($safeKey . '.' . $this->config['file']['extension']), true);
        } catch (Exception $e) {
            throw new CacheException($e->getMessage(), $e->getCode(), $e);
            return false;
        }
    }
}
