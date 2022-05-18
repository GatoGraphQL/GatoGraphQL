<?php
use PoP\ComponentModel\ComponentProcessors\AbstractDataloadComponentProcessor;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\BuildSystemMutationResolverBridge;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\GenerateSystemMutationResolverBridge;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\InstallSystemMutationResolverBridge;

class PoP_System_Module_Processor_SystemActions extends AbstractDataloadComponentProcessor
{
    public final const MODULE_DATALOADACTION_SYSTEM_BUILD = 'dataloadaction-system-build';
    public final const MODULE_DATALOADACTION_SYSTEM_GENERATE = 'dataloadaction-system-generate';
    public final const MODULE_DATALOADACTION_SYSTEM_INSTALL = 'dataloadaction-system-install';

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

    public function getComponentMutationResolverBridge(array $module): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOADACTION_SYSTEM_BUILD:
                return $this->instanceManager->getInstance(BuildSystemMutationResolverBridge::class);

            case self::MODULE_DATALOADACTION_SYSTEM_GENERATE:
                return $this->instanceManager->getInstance(GenerateSystemMutationResolverBridge::class);

            case self::MODULE_DATALOADACTION_SYSTEM_INSTALL:
                return $this->instanceManager->getInstance(InstallSystemMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($module);
    }
}



