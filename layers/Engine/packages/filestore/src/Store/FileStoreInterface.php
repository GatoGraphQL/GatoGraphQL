<?php

declare(strict_types=1);

namespace PoP\FileStore\Store;

use PoP\FileStore\File\AbstractFile;

interface FileStoreInterface
{
    /**
     * @param mixed $contents
     */
    public function save(AbstractFile $file, $contents): void;
    public function delete(AbstractFile $file): bool;
    /**
     * @return mixed
     */
    public function get(AbstractFile $file);
}
