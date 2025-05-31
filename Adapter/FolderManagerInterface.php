<?php

namespace Phantom\Cache\Adapter;

interface FolderManagerInterface
{
    public function new(string $name, int $permission = 0777, bool $recursive = false): bool;
    public function del(string $name): bool;
    public function rename(string $from, string $to): bool;
    public function clear(): bool;
    public function copy(string $from, string $to): bool;
    public function move(string $from, string $to): bool;
    public function exists(string $name): bool;
    public function getRoot(): string;
    public function listFolders(?string $folder = null): array;
    public function listFiles(?string $folder = null): array;
    public function clearFolder(string $folder): bool;
    public function deleteRecursive(string $folder): bool;
    public function getLastModifiedFile(string $folder): ?string;
    public function getFolderSizeRecursive(string $folder): int;
    public function listAllFilesRecursive(string $folder = ''): array;
    public function isEmpty(string $folder): bool;
    public function getFolderSize(string $folder): int;
    public function hasAccess(string $folder): bool;
    public function setPermissions(string $folder, int $permission): bool;
    public function getPermissions(string $folder): int;
    public static function sanitizeFolderName(string $name): string;
}
