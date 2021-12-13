<?php

declare(strict_types=1);

class ComposerScripts
{
    /**
     * @see https://stackoverflow.com/a/3349792
     * @see https://stackoverflow.com/a/33059445
     */
    public static function deleteDir(
        string $dirPath,
        bool $removeDir = true
    ): void {
        if (!file_exists($dirPath)) {
            return;
        }
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '{,.}[!.,!..]*', GLOB_MARK|GLOB_BRACE);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        // Do not remove the root /cache folder,
        // as to avoid throwing an exception when running this script twice,
        // i.e. before the cache/ folder was regenerated
        if ($removeDir) {
            rmdir($dirPath);
        }
    }
}

/**
 * Must pass the path to the dir as 1st argument when calling the script:
 * "@php purge-cache.php $dir"
 */
$dir = $argv[1];
ComposerScripts::deleteDir($dir, false);