<?php
use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostTypeResolver;

class PoP_Posts_Module_Processor_FieldDataloads extends AbstractRelationalFieldDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;

    public const MODULE_DATALOAD_RELATIONALFIELDS_SINGLEPOST = 'dataload-relationalfields-singlepost';
    public const MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST = 'dataload-relationalfields-postlist';
    public const MODULE_DATALOAD_RELATIONALFIELDS_POSTCOUNT = 'dataload-relationalfields-postcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEPOST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_POSTCOUNT],
        );
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEPOST:
                return $this->getQueriedDBObjectID($module, $props, $data_properties);
        }

        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getTypeResolverClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEPOST:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST:
                return CustomPostTypeResolver::class;
        }

        return parent::getTypeResolverClass($module);
    }

    public function getQueryInputOutputHandlerClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST:
                return ListQueryInputOutputHandler::class;
        }

        return parent::getQueryInputOutputHandlerClass($module);
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST:
                return [PoP_Posts_Module_Processor_CustomFilterInners::class, PoP_Posts_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_POSTS];
            case self::MODULE_DATALOAD_RELATIONALFIELDS_POSTCOUNT:
                return [PoP_Posts_Module_Processor_CustomFilterInners::class, PoP_Posts_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_POSTCOUNT];
        }

        return parent::getFilterSubmodule($module);
    }
}



