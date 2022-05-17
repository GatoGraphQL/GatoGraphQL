<?php

declare(strict_types=1);

namespace PoP\DefinitionPersistence;

use PoP\Root\App;
use PoP\FileStore\File\AbstractFile;

class DefinitionPersistenceFile extends AbstractFile
{
    public function getDir(): string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getDefinitionPersistenceBuildDir();
    }

    public function getFilename(): string
    {
        return 'definitions.json';
    }
}
