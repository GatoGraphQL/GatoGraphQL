<?php
use PoP\ComponentModel\ModuleProcessors\AbstractDataloadModuleProcessor;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\SaveDefinitionFileMutationResolverBridge;

class PoP_PersistentDefinitionsSystem_Module_Processor_SystemActions extends AbstractDataloadModuleProcessor
{
    public const MODULE_DATALOADACTION_SYSTEM_SAVEDEFINITIONFILE = 'dataloadaction-system-savedefinitionfile';

    // use PoP_PersistentDefinitionsSystem_Module_Processor_SystemActionsTrait;
    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOADACTION_SYSTEM_SAVEDEFINITIONFILE],
        );
    }

    public function shouldExecuteMutation(array $module, array &$props): bool
    {
        // The actionexecution is triggered directly
        switch ($module[1]) {
            case self::MODULE_DATALOADACTION_SYSTEM_SAVEDEFINITIONFILE:
                return true;
        }

        return parent::shouldExecuteMutation($module, $props);
    }

    public function getComponentMutationResolverBridgeClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOADACTION_SYSTEM_SAVEDEFINITIONFILE:
                return SaveDefinitionFileMutationResolverBridge::class;
        }

        return parent::getComponentMutationResolverBridgeClass($module);
    }
}



