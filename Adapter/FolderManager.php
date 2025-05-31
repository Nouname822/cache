<?php

namespace Phantom\Cache\Adapter;

use Exception;
use Phantom\Cache\Exception\FolderException;

class FolderManager implements FolderManagerInterface
{
    public function __construct(
        private string $root
    ) {}

    public function new(string $name, int $permission = 0777, bool $recursive = false): bool
    {
        try {
            return mkdir($this->root . '/' . $name, $permission, $recursive);
        } catch (Exception $e) {
            throw new FolderException($e->getMessage(), $e->getCode(), $e);
            return false;
        }
    }

    public function del(string $name): bool
    {
        try {
            return rmdir($this->root . '/' . $name);
        } catch (Exception $e) {
            throw new FolderException($e->getMessage(), $e->getCode(), $e);
            return false;
        }
    }

    public function rename(string $from, string $to): bool
    {
        try {
            return rename($this->root . '/' . $from, $this->root . '/' . $to);
        } catch (Exception $e) {
            throw new FolderException($e->getMessage(), $e->getCode(), $e);
            return false;
        }
    }

    public function clear(): bool
    {
        try {
            return rmdir($this->root);
        } catch (Exception $e) {
            throw new FolderException($e->getMessage(), $e->getCode(), $e);
            return false;
        }
    }

    public function copy(string $from, string $to): bool
    {
        try {
            return copy($this->root . '/' . $from, $this->root . '/' . $to);
        } catch (Exception $e) {
            throw new FolderException($e->getMessage(), $e->getCode(), $e);
            return false;
        }
    }

    public function move(string $from, string $to): bool
    {
        try {
            return rename($this->root . '/' . $from, $this->root . '/' . $to);
        } catch (Exception $e) {
            throw new FolderException($e->getMessage(), $e->getCode(), $e);
            return false;
        }
    }

    public function exists(string $name): bool
    {
        return file_exists($this->root . '/' . $name);
    }

    public function getRoot(): string
    {
        return $this->root;
    }

    public function listFolders(?string $folder = null): array
    {
        $folders = [];
        $path = $this->root . '/' . ($folder !== null ? trim($folder, '/') : '');
        $path = rtrim($path, '/');

        if (!is_dir($path)) {
            return [];
        }

        foreach (scandir($path) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            if (is_dir($path . '/' . $file)) {
                $folders[] = $file;
            }
        }

        return $folders;
    }

    public function listFiles(?string $folder = null): array
    {
        $files = [];
        $path = $this->root . '/' . ($folder !== null ? trim($folder, '/') : '');
        $path = rtrim($path, '/');

        if (!is_dir($path)) {
            return [];
        }

        foreach (scandir($path) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            if (is_file($path . '/' . $file)) {
                $files[] = $file;
            }
        }

        return $files;
    }

    public function clearFolder(string $folder): bool
    {
        $path = $this->root . '/' . trim($folder, '/');
        if (!is_dir($path)) {
            return false;
        }

        $files = array_diff(scandir($path), ['.', '..']);
        foreach ($files as $file) {
            $fullPath = $path . '/' . $file;
            if (is_dir($fullPath)) {
                $this->deleteRecursive($fullPath);
            } else {
                unlink($fullPath);
            }
        }

        return true;
    }

    public function deleteRecursive(string $folder): bool
    {
        $path = $this->root . '/' . trim($folder, '/');
        if (!file_exists($path)) {
            return false;
        }

        if (is_file($path)) {
            return unlink($path);
        }

        $items = array_diff(scandir($path), ['.', '..']);
        foreach ($items as $item) {
            $itemPath = $path . '/' . $item;
            is_dir($itemPath) ? $this->deleteRecursive($itemPath) : unlink($itemPath);
        }

        return rmdir($path);
    }

    public function getLastModifiedFile(string $folder): ?string
    {
        $path = $this->root . '/' . trim($folder, '/');
        if (!is_dir($path)) {
            return null;
        }

        $files = array_filter(scandir($path), function ($file) use ($path) {
            return is_file($path . '/' . $file);
        });

        $latestTime = 0;
        $latestFile = null;

        foreach ($files as $file) {
            $mtime = filemtime($path . '/' . $file);
            if ($mtime > $latestTime) {
                $latestTime = $mtime;
                $latestFile = $file;
            }
        }

        return $latestFile;
    }

    public function getFolderSizeRecursive(string $folder): int
    {
        $path = $this->root . '/' . trim($folder, '/');
        if (!is_dir($path)) {
            return 0;
        }

        $size = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            $size += $file->getSize();
        }

        return $size;
    }

    public function listAllFilesRecursive(string $folder = ''): array
    {
        $path = $this->root . '/' . trim($folder, '/');
        $files = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    public static function sanitizeFolderName(string $name): string
    {
        $name = preg_replace('/[\/\\\\:*?"<>|]/', '', $name);

        $name = trim($name);

        $name = preg_replace('/[^\w\s.-]/u', '', $name);

        $name = preg_replace('/[. ]+$/', '', $name);

        return mb_substr($name, 0, 255);
    }


    public function isEmpty(string $folder): bool
    {
        $path = $this->root . '/' . trim($folder, '/');
        return is_dir($path) && count(scandir($path)) === 2;
    }

    public function getFolderSize(string $folder): int
    {
        return sizeof(scandir($this->root . '/' . $folder));
    }

    public function hasAccess(string $folder): bool
    {
        return is_writable($this->root . '/' . $folder);
    }

    public function setPermissions(string $folder, int $permission): bool
    {
        return chmod($this->root . '/' . $folder, $permission);
    }

    public function compress(string $folder): bool
    {
        return exec('zip -r ' . $this->root . '/' . $folder . '.zip ' . $this->root . '/' . $folder);
    }

    public function decompress(string $folder): bool
    {
        return exec('unzip ' . $this->root . '/' . $folder . '.zip -d ' . $this->root . '/' . $folder);
    }

    public function search(string $folder, string $pattern): array
    {
        return glob($this->root . '/' . $folder . '/' . $pattern);
    }

    public function getOwner(string $folder): string
    {
        return fileowner($this->root . '/' . $folder);
    }

    public function filterFiles(string $folder, string $extension): array
    {
        return glob($this->root . '/' . $folder . '/*.' . $extension);
    }

    public function getPermissions(string $folder): int
    {
        return fileperms($this->root . '/' . $folder);
    }
}
