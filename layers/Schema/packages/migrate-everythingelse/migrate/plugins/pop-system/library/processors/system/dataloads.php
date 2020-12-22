<?php
use PoP\ComponentModel\ModuleProcessors\AbstractDataloadModuleProcessor;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\BuildSystemMutationResolverBridge;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\InstallSystemMutationResolverBridge;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\GenerateSystemMutationResolverBridge;

class PoP_System_Module_Processor_SystemActions extends AbstractDataloadModuleProcessor
{
    public const MODULE_DATALOADACTION_SYSTEM_BUILD = 'dataloadaction-system-build';
    public const MODULE_DATALOADACTION_SYSTEM_GENERATE = 'dataloadaction-system-generate';
    public const MODULE_DATALOADACTION_SYSTEM_INSTALL = 'dataloadaction-system-install';

    // use PoP_System_Module_Processor_SystemActionsTrait;
    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOADACTION_SYSTEM_BUILD],
            [self::class, self::MODULE_DATALOADACTION_SYSTEM_GENERATE],
            [self::class, self::MODULE_DATALOADACTION_SYSTEM_INSTALL],
        );
    }

    public function shouldExecuteMutation(array $module, array &$props): bool
    {

        // The actionexecution is triggered directly
        switch ($module[1]) {
            case self::MODULE_DATALOADACTION_SYSTEM_BUILD:
            case self::MODULE_DATALOADACTION_SYSTEM_GENERATE:
            case self::MODULE_DATALOADACTION_SYSTEM_INSTALL:
                return true;
        }

        return parent::shouldExecuteMutation($module, $props);
    }

    public function getComponentMutationResolverBridgeClass(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOADACTION_SYSTEM_BUILD:
                return BuildSystemMutationResolverBridge::class;

            case self::MODULE_DATALOADACTION_SYSTEM_GENERATE:
                return GenerateSystemMutationResolverBridge::class;

            case self::MODULE_DATALOADACTION_SYSTEM_INSTALL:
                return InstallSystemMutationResolverBridge::class;
        }

        return parent::getComponentMutationResolverBridgeClass($module);
    }
}



