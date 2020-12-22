<?php
use PoP\ComponentModel\ModuleProcessors\AbstractDataloadModuleProcessor;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\GenerateThemeMutationResolverBridge;

class PoP_System_Theme_Module_Processor_SystemActions extends AbstractDataloadModuleProcessor
{
    public const MODULE_DATALOADACTION_SYSTEM_GENERATETHEME = 'dataloadaction-system-generate-theme';

    // use PoP_System_Theme_Module_Processor_SystemActionsTrait;
    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOADACTION_SYSTEM_GENERATETHEME],
        );
    }

    public function shouldExecuteMutation(array $module, array &$props): bool
    {

        // The actionexecution is triggered directly
        switch ($module[1]) {
            case self::MODULE_DATALOADACTION_SYSTEM_GENERATETHEME:
                return true;
        }

        return parent::shouldExecuteMutation($module, $props);
    }

    public function getComponentMutationResolverBridgeClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOADACTION_SYSTEM_GENERATETHEME:
                return GenerateThemeMutationResolverBridge::class;
        }

        return parent::getComponentMutationResolverBridgeClass($module);
    }
}



