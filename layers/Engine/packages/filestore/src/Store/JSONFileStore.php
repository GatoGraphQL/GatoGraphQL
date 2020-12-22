<?php

declare(strict_types=1);

namespace PoP\FileStore\Store;

use PoP\FileStore\File\AbstractFile;

class JSONFileStore extends FileStore
{
    /**
     * @param mixed $contents
     */
    public function save(AbstractFile $file, $contents): void
    {
        // Encode it and save it
        parent::save($file, json_encode($contents));
    }

    /**
     * @return mixed
     */
    public function get(AbstractFile $file)
    {
        $contents = parent::get($file);
        if (!is_null($contents)) {
            // Decode it
            return json_decode($contents, true);
        }
        return array();
    }
}
