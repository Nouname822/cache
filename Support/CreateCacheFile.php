<?php

namespace Nurymbet\Phantom\Cache\Support;

use Exception;
use Nurymbet\Phantom\Cache\Adapter\FileManager;
use Nurymbet\Phantom\Cache\Adapter\FolderManager;
use Nurymbet\Phantom\Cache\Exception\CacheException;
use Nurymbet\Phantom\Cache\Setting;

class CreateCacheFile
{
    private string $namespace;
    private string $key;
    private mixed $value;
    private array $config;

    public function __construct(string $namespace, string $key, mixed $value)
    {
        $this->namespace = $namespace;
        $this->key = $key;
        $this->value = $value;
        $this->config = Setting::get();
    }

    public function main(): bool
    {
        try {
            $safeNamespace = FolderManager::sanitizeFolderName($this->namespace);
            $safeKey = FolderManager::sanitizeFolderName($this->key);

            $folderPath = rtrim($this->config['file']['path'], '/') . '/' . $safeNamespace;
            $fileName = $safeKey . '.' . $this->config['file']['extension'];
            $content = json_encode($this->value);

            $folderManager = new FolderManager($folderPath);
            $fileManager = new FileManager($folderPath);

            if ((new CheckCacheFile($this->namespace, $this->key))->main()) {
                $fileManager->write($fileName, $content);
            } else {
                if (!$folderManager->exists('')) {
                    $folderManager->new('', 0777, true);
                }

                $fileManager->new($fileName, $content);
            }


            return true;
        } catch (Exception $e) {
            throw new CacheException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
