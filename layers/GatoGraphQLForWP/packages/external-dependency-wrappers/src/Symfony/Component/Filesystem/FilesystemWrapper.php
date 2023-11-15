<?php

declare(strict_types=1);

namespace GatoGraphQL\ExternalDependencyWrappers\Symfony\Component\Filesystem;

use Exception;
use GatoGraphQL\ExternalDependencyWrappers\Symfony\Component\Exception\IOException;
use PoP\ComponentModel\Misc\GeneralUtils;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Wrapper for Symfony\Component\Filesystem\Filesystem
 */
class FilesystemWrapper
{
    private readonly Filesystem $fileSystem;

    public function __construct()
    {
        $this->fileSystem = new Filesystem();
    }

    /**
     * Removes files or directories.
     *
     * @param string|iterable<mixed> $files A filename, an array of files, or a \Traversable instance to remove
     *
     * @throws IOException When removal fails
     */
    public function remove(string|iterable $files): void
    {
        try {
            $this->fileSystem->remove($files);
        } catch (Exception $e) {
            if (is_string($files)) {
                $fileItems = $files;
            } else {
                $fileItems = implode(', ', GeneralUtils::iterableToArray($files));
            }
            // Throw own exception
            throw new IOException(
                \sprintf(
                    'Could not remove file(s) or folder(s): %s',
                    $fileItems
                ),
                0,
                $e
            );
        }
    }
}
