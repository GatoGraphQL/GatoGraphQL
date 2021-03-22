<?php

declare(strict_types=1);

namespace PoP\FileStore\Store;

use PoP\FileStore\File\AbstractFile;

class JSONFileStore extends FileStore
{
    public function save(AbstractFile $file, mixed $contents): void
    {
        // Encode it and save it
        parent::save($file, json_encode($contents));
    }

    public function get(AbstractFile $file): mixed
    {
        $contents = parent::get($file);
        if (!is_null($contents)) {
            // Decode it
            return json_decode($contents, true);
        }
        return array();
    }
}
