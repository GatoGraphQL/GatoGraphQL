<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\ConditionalOnComponent\API\ModuleProcessors;

use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoPSchema\PostMutations\ModuleProcessors\PostMutationFilterInputContainerModuleProcessor;
use PoPSchema\Posts\TypeResolvers\Object\PostTypeResolver;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;

class FieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTLIST = 'dataload-relationalfields-mypostlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTCOUNT = 'dataload-relationalfields-mypostcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTCOUNT],
        );
    }

    public function getRelationalTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTCOUNT:
                return PostTypeResolver::class;
        }

        return parent::getRelationalTypeResolverClass($module);
    }

    public function getQueryInputOutputHandlerClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTLIST:
                return ListQueryInputOutputHandler::class;
        }

        return parent::getQueryInputOutputHandlerClass($module);
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTLIST:
                return [PostMutationFilterInputContainerModuleProcessor::class, PostMutationFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MYPOSTS];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_MYPOSTCOUNT:
                return [PostMutationFilterInputContainerModuleProcessor::class, PostMutationFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MYPOSTCOUNT];
        }

        return parent::getFilterSubmodule($module);
    }
}
