<?php

declare(strict_types=1);

namespace GatoGraphQL\ExternalDependencyWrappers\Symfony\Component\Filesystem;

use Exception;
use GatoGraphQL\ExternalDependencyWrappers\Symfony\Component\Exception\IOException;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Services\StandaloneServiceTrait;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Wrapper for Symfony\Component\Filesystem\Filesystem
 */
class FilesystemWrapper
{
    use StandaloneServiceTrait;

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
                $fileItems = implode(
                    $this->getTranslationAPI()->__(', ', 'external-dependency-wrappers'),
                    GeneralUtils::iterableToArray($files)
                );
            }
            // Throw own exception
            throw new IOException(
                \sprintf(
                    $this->getTranslationAPI()->__('Could not remove file(s) or folder(s): %s', 'external-dependency-wrappers'),
                    $fileItems
                ),
                0,
                $e
            );
        }
    }
}
