<?php

namespace Nurymbet\Phantom\Cache\Contract;

interface FileManagerInterface
{
    public function __construct(string $root);

    public function new(string $file, string $content = ''): bool;
    public function del(string $file): bool;
    public function read(string $file): string;
    public function write(string $file, string $content, bool $append = false): bool;
    public function rename(string $from, string $to): bool;
    public function copy(string $from, string $to): bool;
    public function exists(string $file): bool;
    public function getSize(string $file): int|false;
    public function getMimeType(string $file): string|false;
    public function getExtension(string $file): string;
    public function getBasename(string $file): string;
    public function getDirname(string $file): string;
    public function getPermissions(string $file): int|false;
    public function setPermissions(string $file, int $permissions): bool;
    public function getOwner(string $file): int|false;
}
