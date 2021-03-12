<?php

declare(strict_types=1);

namespace PoP\Engine\ModuleProcessors;

use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;

class RootModuleProcessors extends AbstractModuleProcessor
{
    public const MODULE_EMPTY = 'empty';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EMPTY],
        );
    }
}
