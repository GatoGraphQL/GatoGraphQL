<?php

declare(strict_types=1);

namespace PoPSchema\Events\ModuleProcessors;

use PoPSchema\Events\TypeResolvers\EventTypeResolver;
use PoPSchema\Events\ModuleProcessors\FilterInnerModuleProcessor;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;
use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;

class RelationalFieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_FILTER_EVENTLIST = 'dataload-filter-eventlist';
    public const MODULE_DATALOAD_FILTER_EVENTCOUNT = 'dataload-filter-eventcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_FILTER_EVENTLIST],
            [self::class, self::MODULE_DATALOAD_FILTER_EVENTCOUNT],
        );
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_FILTER_EVENTLIST:
                return EventTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function getQueryInputOutputHandlerClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_FILTER_EVENTLIST:
                return ListQueryInputOutputHandler::class;
        }

        return parent::getQueryInputOutputHandlerClass($module);
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_FILTER_EVENTLIST:
                return [FilterInnerModuleProcessor::class, FilterInnerModuleProcessor::MODULE_FILTERINNER_EVENTLIST];
            case self::MODULE_DATALOAD_FILTER_EVENTCOUNT:
                return [FilterInnerModuleProcessor::class, FilterInnerModuleProcessor::MODULE_FILTERINNER_EVENTCOUNT];
        }

        return parent::getFilterSubmodule($module);
    }
}
