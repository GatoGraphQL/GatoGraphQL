<?php

declare(strict_types=1);

namespace PoP\FileStore\Store;

use PoP\FileStore\File\AbstractFile;

interface FileStoreInterface
{
    public function save(AbstractFile $file, mixed $contents): void;
    public function delete(AbstractFile $file): bool;
    public function get(AbstractFile $file): mixed;
}
