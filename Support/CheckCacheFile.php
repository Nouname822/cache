<?php

namespace Phantom\Cache\Support;

use Exception;
use Phantom\Cache\Adapter\FileManager;
use Phantom\Cache\Adapter\FolderManager;
use Phantom\Cache\Exception\CacheException;
use Phantom\Cache\Setting;

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
