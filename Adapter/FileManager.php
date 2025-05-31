<?php

namespace Phantom\Cache\Adapter;

use Exception;
use Phantom\Cache\Exception\FileException;

class FileManager implements FileManagerInterface
{
    public function __construct(
        private string $root
    ) {}

    private function fullPath(string $file): string
    {
        return rtrim($this->root, '/') . '/' . ltrim($file, '/');
    }

    public function new(string $file, string $content = ''): bool
    {
        try {
            return file_put_contents($this->fullPath($file), $content) !== false;
        } catch (Exception $e) {
            throw new FileException("Ошибка при создании файла: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function del(string $file): bool
    {
        try {
            return unlink($this->fullPath($file));
        } catch (Exception $e) {
            throw new FileException("Ошибка при удалении файла: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function read(string $file): string
    {
        try {
            return file_get_contents($this->fullPath($file));
        } catch (Exception $e) {
            throw new FileException("Ошибка при чтении файла: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function write(string $file, string $content, bool $append = false): bool
    {
        try {
            $flags = $append ? FILE_APPEND : 0;
            return file_put_contents($this->fullPath($file), $content, $flags) !== false;
        } catch (Exception $e) {
            throw new FileException("Ошибка при записи в файл: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function rename(string $from, string $to): bool
    {
        try {
            return rename($this->fullPath($from), $this->fullPath($to));
        } catch (Exception $e) {
            throw new FileException("Ошибка при переименовании файла: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function copy(string $from, string $to): bool
    {
        try {
            return copy($this->fullPath($from), $this->fullPath($to));
        } catch (Exception $e) {
            throw new FileException("Ошибка при копировании файла: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function exists(string $file): bool
    {
        return is_file($this->fullPath($file));
    }

    public function getSize(string $file): int|false
    {
        return filesize($this->fullPath($file));
    }

    public function getMimeType(string $file): string|false
    {
        return mime_content_type($this->fullPath($file));
    }

    public function getExtension(string $file): string
    {
        return pathinfo($this->fullPath($file), PATHINFO_EXTENSION);
    }

    public function getBasename(string $file): string
    {
        return basename($this->fullPath($file));
    }

    public function getDirname(string $file): string
    {
        return dirname($this->fullPath($file));
    }

    public function getPermissions(string $file): int|false
    {
        return fileperms($this->fullPath($file));
    }

    public function setPermissions(string $file, int $permissions): bool
    {
        return chmod($this->fullPath($file), $permissions);
    }

    public function getOwner(string $file): int|false
    {
        return fileowner($this->fullPath($file));
    }
}
