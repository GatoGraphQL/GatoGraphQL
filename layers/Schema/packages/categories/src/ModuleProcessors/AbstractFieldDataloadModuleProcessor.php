<?php

declare(strict_types=1);

namespace PoPSchema\Categories\ModuleProcessors;

use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;

abstract class AbstractFieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
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

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORY:
                return $this->getQueriedDBObjectID($module, $props, $data_properties);
        }

        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
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
                return [FilterInnerModuleProcessor::class, FilterInnerModuleProcessor::MODULE_FILTERINNER_CATEGORIES];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYCOUNT:
                return [FilterInnerModuleProcessor::class, FilterInnerModuleProcessor::MODULE_FILTERINNER_CATEGORYCOUNT];
        }

        return parent::getFilterSubmodule($module);
    }
}
