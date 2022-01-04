<?php

declare(strict_types=1);

namespace PoP\DefinitionPersistence;

use PoP\Root\Managers\ComponentManager;
use PoP\FileStore\File\AbstractFile;

class DefinitionPersistenceFile extends AbstractFile
{
    public function getDir(): string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getDefinitionPersistenceBuildDir();
    }

    public function getFilename(): string
    {
        return 'definitions.json';
    }
}
