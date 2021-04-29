<?php

declare(strict_types=1);

class ComposerScripts
{
    /**
     * @see https://stackoverflow.com/a/3349792
     */
    public static function deleteDir(string $dirPath): void
    {
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
}

/**
 * Must pass the path to the dir as 1st argument when calling the script:
 * "@php purge-cache.php $dir"
 */
$dir = $argv[1];
ComposerScripts::deleteDir($dir);