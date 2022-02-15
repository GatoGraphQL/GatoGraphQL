<?php

declare(strict_types=1);

namespace GraphQLAPI\ExternalDependencyWrappers\Symfony\Component\Filesystem;

use GraphQLAPI\ExternalDependencyWrappers\Symfony\Component\Exception\IOException;
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
     * @throws IOException When removal fails
     */
    public function remove(string|iterable $files): void
    {
        try {
            $this->fileSystem->remove($files);
        } catch (IOExceptionInterface $e) {
            // Throw own exception
            throw new IOException($e->getMessage(), 0, $e);
        }
    }
}
