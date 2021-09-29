<?php

declare(strict_types=1);

namespace PoP\DefinitionPersistence;

use PoP\FileStore\File\AbstractFile;

class DefinitionPersistenceFile extends AbstractFile
{
    public function getDir(): string
    {
        return Component::getBuildDir();
    }

    public function getFilename(): string
    {
        return 'definitions.json';
    }
}
