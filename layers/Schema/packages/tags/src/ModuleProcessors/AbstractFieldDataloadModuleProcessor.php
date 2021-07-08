<?php

declare(strict_types=1);

namespace PoPSchema\Tags\ModuleProcessors;

use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;
use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;
use PoPSchema\Tags\ModuleProcessors\FilterInnerModuleProcessor;

abstract class AbstractFieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_RELATIONALFIELDS_TAG = 'dataload-relationalfields-tag';
    public const MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST = 'dataload-relationalfields-taglist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_TAGCOUNT = 'dataload-relationalfields-tagcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_TAG],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_TAGCOUNT],
        );
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAG:
                return $this->getQueriedDBObjectID($module, $props, $data_properties);
        }

        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAG:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return PostTagTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function getQueryInputOutputHandlerClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return ListQueryInputOutputHandler::class;
        }

        return parent::getQueryInputOutputHandlerClass($module);
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return [FilterInnerModuleProcessor::class, FilterInnerModuleProcessor::MODULE_FILTERINNER_TAGS];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGCOUNT:
                return [FilterInnerModuleProcessor::class, FilterInnerModuleProcessor::MODULE_FILTERINNER_TAGCOUNT];
        }

        return parent::getFilterSubmodule($module);
    }
}
