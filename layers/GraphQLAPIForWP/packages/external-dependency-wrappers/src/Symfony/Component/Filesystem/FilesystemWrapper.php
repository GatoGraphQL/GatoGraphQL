<?php

declare(strict_types=1);

namespace GraphQLAPI\ExternalDependencyWrappers\Symfony\Component\Filesystem;

use RuntimeException;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Wrapper for Symfony\Component\Filesystem\Filesystem
 */
class FilesystemWrapper
{
    private Filesystem $fileSystem;

    public function __construct()
    {
        $this->fileSystem = new Filesystem();
    }

    /**
     * Removes files or directories.
     *
     * @param string|iterable $files A filename, an array of files, or a \Traversable instance to remove
     *
     * @throws RuntimeException When removal fails
     */
    public function remove(string|iterable $files): void
    {
        try {
            $this->fileSystem->remove($files);
        } catch (IOExceptionInterface $e) {
            // Throw own exception
            throw new RuntimeException($e->getMessage());
        }
    }
}
