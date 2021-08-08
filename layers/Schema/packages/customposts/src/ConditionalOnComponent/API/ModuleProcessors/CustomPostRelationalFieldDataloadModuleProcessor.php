<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\ConditionalOnComponent\API\ModuleProcessors;

use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoPSchema\CustomPosts\ModuleProcessors\CustomPostFilterInputContainerModuleProcessor;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;

class CustomPostRelationalFieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST = 'dataload-relationalfields-singlecustompost';
    public const MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST = 'dataload-relationalfields-unioncustompostlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTCOUNT = 'dataload-relationalfields-unioncustompostcount';
    public const MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST = 'dataload-relationalfields-adminunioncustompostlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTCOUNT = 'dataload-relationalfields-adminunioncustompostcount';
    public const MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST = 'dataload-relationalfields-custompostlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTCOUNT = 'dataload-relationalfields-custompostcount';
    public const MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST = 'dataload-relationalfields-admincustompostlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTCOUNT = 'dataload-relationalfields-admincustompostcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTCOUNT],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTCOUNT],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTCOUNT],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTCOUNT],
        );
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST:
                return $this->getQueriedDBObjectID($module, $props, $data_properties);
        }

        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLECUSTOMPOST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST:
                return CustomPostUnionTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function getQueryInputOutputHandlerClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST:
                return ListQueryInputOutputHandler::class;
        }

        return parent::getQueryInputOutputHandlerClass($module);
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST:
                return [
                    CustomPostFilterInputContainerModuleProcessor::class,
                    CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINNER_UNIONCUSTOMPOSTLIST
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTCOUNT:
                return [
                    CustomPostFilterInputContainerModuleProcessor::class,
                    CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINNER_UNIONCUSTOMPOSTCOUNT
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTLIST:
                return [
                    CustomPostFilterInputContainerModuleProcessor::class,
                    CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINNER_ADMINUNIONCUSTOMPOSTLIST
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUNIONCUSTOMPOSTCOUNT:
                return [
                    CustomPostFilterInputContainerModuleProcessor::class,
                    CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINNER_ADMINUNIONCUSTOMPOSTCOUNT
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST:
                return [
                    CustomPostFilterInputContainerModuleProcessor::class,
                    CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINNER_CUSTOMPOSTLISTLIST
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTCOUNT:
                return [
                    CustomPostFilterInputContainerModuleProcessor::class,
                    CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINNER_CUSTOMPOSTLISTCOUNT
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTLIST:
                return [
                    CustomPostFilterInputContainerModuleProcessor::class,
                    CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINNER_ADMINCUSTOMPOSTLISTLIST
                ];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINCUSTOMPOSTCOUNT:
                return [
                    CustomPostFilterInputContainerModuleProcessor::class,
                    CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINNER_ADMINCUSTOMPOSTLISTCOUNT
                ];
        }

        return parent::getFilterSubmodule($module);
    }
}
