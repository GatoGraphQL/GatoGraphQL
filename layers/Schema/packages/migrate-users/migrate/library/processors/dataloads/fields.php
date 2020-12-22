<?php
use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

class PoP_Users_Module_Processor_FieldDataloads extends AbstractRelationalFieldDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_RELATIONALFIELDS_SINGLEUSER = 'dataload-relationalfields-singleuser';
    public const MODULE_DATALOAD_RELATIONALFIELDS_USERLIST = 'dataload-relationalfields-userlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_USERCOUNT = 'dataload-relationalfields-usercount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEUSER],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_USERCOUNT],
        );
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties)
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
                return UserTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function getQueryInputOutputHandlerClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST:
                return ListQueryInputOutputHandler::class;
        }

        return parent::getQueryInputOutputHandlerClass($module);
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST:
                return [PoP_Users_Module_Processor_CustomFilterInners::class, PoP_Users_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_USERS];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_USERCOUNT:
                return [PoP_Users_Module_Processor_CustomFilterInners::class, PoP_Users_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_USERCOUNT];
        }

        return parent::getFilterSubmodule($module);
    }
}



