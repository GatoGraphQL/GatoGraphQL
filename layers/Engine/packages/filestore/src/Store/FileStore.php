<?php

declare(strict_types=1);

namespace PoP\FileStore\Store;

use RuntimeException;
use PoP\FileStore\File\AbstractFile;

class FileStore implements FileStoreInterface
{
    public function save(AbstractFile $file, mixed $contents): void
    {
        $filePath = $file->getFilepath();

        // Create the directory
        $dir = dirname($filePath);
        if (!file_exists($dir)) {
            // Create folder
            if (@mkdir($dir, 0777, true) === false) {
                throw new RuntimeException('The directory ' . $dir . ' could not be created.');
            }
        }

        // Open the file, write content and close it
        $handle = fopen($filePath, "wb");
        if ($handle === false) {
            throw new RuntimeException('The file ' . $filePath . ' could not be opened.');
        }
        fwrite($handle, $contents);
        fclose($handle);
    }

    public function delete(AbstractFile $file): bool
    {
        $filePath = $file->getFilepath();

        if (file_exists($filePath)) {
            unlink($filePath);
            return true;
        }

        return false;
    }

    public function get(AbstractFile $file): mixed
    {
        $filePath = $file->getFilepath();
        if (file_exists($filePath)) {
            // Return the file contents
            $contents = file_get_contents($filePath);
            if ($contents === false) {
                return null;
            }
            return $contents;
        }

        return null;
    }
}
