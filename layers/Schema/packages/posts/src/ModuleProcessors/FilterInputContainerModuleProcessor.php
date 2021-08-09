<?php

declare(strict_types=1);

namespace PoPSchema\Posts\ModuleProcessors;

class FilterInputContainerModuleProcessor extends AbstractFilterInputContainerModuleProcessor
{
    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINNER_POSTS],
            [self::class, self::MODULE_FILTERINNER_POSTCOUNT],
            [self::class, self::MODULE_FILTERINNER_ADMINPOSTS],
            [self::class, self::MODULE_FILTERINNER_ADMINPOSTCOUNT],
        );
    }

    public function getSubmodules(array $module): array
    {
        return array_merge(
            parent::getSubmodules($module),
            []
        );
    }
}
