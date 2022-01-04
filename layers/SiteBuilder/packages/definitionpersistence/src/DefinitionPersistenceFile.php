<?php

declare(strict_types=1);

namespace PoP\DefinitionPersistence;

use PoP\FileStore\File\AbstractFile;

class DefinitionPersistenceFile extends AbstractFile
{
    public function getDir(): string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Root\Managers\ComponentManager::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getDefinitionPersistenceBuildDir();
    }

    public function getFilename(): string
    {
        return 'definitions.json';
    }
}
