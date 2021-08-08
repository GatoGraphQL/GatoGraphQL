<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\API\ModuleProcessors;

use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoPSchema\Users\ModuleProcessors\FilterInputContainerModuleProcessor;

class FieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_RELATIONALFIELDS_SINGLEUSER = 'dataload-relationalfields-singleuser';
    public const MODULE_DATALOAD_RELATIONALFIELDS_USERLIST = 'dataload-relationalfields-userlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_USERCOUNT = 'dataload-relationalfields-usercount';
    public const MODULE_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST = 'dataload-relationalfields-adminuserlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_ADMINUSERCOUNT = 'dataload-relationalfields-adminusercount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEUSER],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_USERCOUNT],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUSERCOUNT],
        );
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEUSER:
                return $this->getQueriedDBObjectID($module, $props, $data_properties);
        }

        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEUSER:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST:
                return UserTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function getQueryInputOutputHandlerClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST:
                return ListQueryInputOutputHandler::class;
        }

        return parent::getQueryInputOutputHandlerClass($module);
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST:
                return [FilterInputContainerModuleProcessor::class, FilterInputContainerModuleProcessor::MODULE_FILTERINNER_USERS];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_USERCOUNT:
                return [FilterInputContainerModuleProcessor::class, FilterInputContainerModuleProcessor::MODULE_FILTERINNER_USERCOUNT];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST:
                return [FilterInputContainerModuleProcessor::class, FilterInputContainerModuleProcessor::MODULE_FILTERINNER_ADMINUSERS];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_ADMINUSERCOUNT:
                return [FilterInputContainerModuleProcessor::class, FilterInputContainerModuleProcessor::MODULE_FILTERINNER_ADMINUSERCOUNT];
        }

        return parent::getFilterSubmodule($module);
    }
}
