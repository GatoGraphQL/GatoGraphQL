<?php

declare(strict_types=1);

namespace PoPSchema\Categories\ModuleProcessors;

use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;
use PoPSchema\Categories\TypeResolvers\CategoryTypeResolver;

class FieldDataloads extends AbstractRelationalFieldDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_RELATIONALFIELDS_CATEGORY = 'dataload-relationalfields-category';
    public const MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYLIST = 'dataload-relationalfields-categorylist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYCOUNT = 'dataload-relationalfields-categorycount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORY],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYCOUNT],
        );
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORY:
                return $this->getQueriedDBObjectID($module, $props, $data_properties);
        }

        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORY:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYLIST:
                return CategoryTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function getQueryInputOutputHandlerClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYLIST:
                return ListQueryInputOutputHandler::class;
        }

        return parent::getQueryInputOutputHandlerClass($module);
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYLIST:
                return [PoP_Categories_Module_Processor_CustomFilterInners::class, PoP_Categories_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_CATEGORIES];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYCOUNT:
                return [PoP_Categories_Module_Processor_CustomFilterInners::class, PoP_Categories_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_CATEGORYCOUNT];
        }

        return parent::getFilterSubmodule($module);
    }
}



