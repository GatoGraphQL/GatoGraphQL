<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts\ModuleProcessors;

use PoPSchema\GenericCustomPosts\TypeResolvers\GenericCustomPostTypeResolver;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;
use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;

class GenericCustomPostRelationalFieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTLIST = 'dataload-relationalfields-genericcustompostlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTCOUNT = 'dataload-relationalfields-genericcustompostcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTCOUNT],
        );
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTLIST:
                return GenericCustomPostTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function getQueryInputOutputHandlerClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTLIST:
                return ListQueryInputOutputHandler::class;
        }

        return parent::getQueryInputOutputHandlerClass($module);
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTLIST:
                return [
                    GenericCustomPostFilterInnerModuleProcessor::class,
                    GenericCustomPostFilterInnerModuleProcessor::MODULE_FILTERINNER_GENERICCUSTOMPOSTLIST
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTCOUNT:
                return [
                    GenericCustomPostFilterInnerModuleProcessor::class,
                    GenericCustomPostFilterInnerModuleProcessor::MODULE_FILTERINNER_GENERICCUSTOMPOSTCOUNT
                ];
        }

        return parent::getFilterSubmodule($module);
    }
}
