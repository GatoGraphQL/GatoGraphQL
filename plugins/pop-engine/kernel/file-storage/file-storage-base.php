<?php
namespace PoP\Engine\FileStorage;

abstract class FileStorageBase
{
    public function saveFile($file, $contents)
    {

        // Create the directory structure
        $this->createDir(dirname($file));

        // Open the file, write content and close it
        $handle = fopen($file, "wb");
        $numbytes = fwrite($handle, $contents);
        fclose($handle);

        return $file;
    }

    private function createDir($dir)
    {
        if (!file_exists($dir)) {
            // Create folder
            @mkdir($dir, 0777, true);
        }
    }
}
