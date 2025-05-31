<?php

namespace Nurymbet\Phantom\Cache\Support;

use Exception;
use Nurymbet\Phantom\Cache\Adapter\FileManager;
use Nurymbet\Phantom\Cache\Adapter\FolderManager;
use Nurymbet\Phantom\Cache\Exception\CacheException;
use Nurymbet\Phantom\Cache\Setting;

class CheckCacheFile
{
    private string $namespace;
    private string $key;
    private array $config;

    public function __construct(string $namespace, string $key)
    {
        $this->namespace = $namespace;
        $this->key = $key;
        $this->config = Setting::get();
    }

    public function main(): bool
    {
        try {
            $safeNamespace = FolderManager::sanitizeFolderName($this->namespace);
            $safeKey = FolderManager::sanitizeFolderName($this->key);

            $folderPath = rtrim($this->config['file']['path'], '/') . '/' . $safeNamespace;
            $fileName = $safeKey . '.' . $this->config['file']['extension'];

            $fileManager = new FileManager($folderPath);

            return $fileManager->exists($fileName);
        } catch (Exception $e) {
            throw new CacheException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
