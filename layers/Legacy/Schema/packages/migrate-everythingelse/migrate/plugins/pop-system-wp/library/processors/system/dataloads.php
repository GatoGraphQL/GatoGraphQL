<?php
use PoP\ComponentModel\ComponentProcessors\AbstractDataloadComponentProcessor;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\ActivatePluginsMutationResolverBridge;

class PoP_SystemWP_WP_Module_Processor_SystemActions extends AbstractDataloadComponentProcessor
{
    public final const MODULE_DATALOADACTION_SYSTEM_ACTIVATEPLUGINS = 'dataloadaction-system-activateplugins';

    // use PoP_SystemWP_WP_Module_Processor_SystemActionsTrait;
    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOADACTION_SYSTEM_ACTIVATEPLUGINS],
        );
    }

    public function shouldExecuteMutation(array $module, array &$props): bool
    {

        // The actionexecution is triggered directly
        switch ($module[1]) {
            case self::MODULE_DATALOADACTION_SYSTEM_ACTIVATEPLUGINS:
                return true;
        }

        return parent::shouldExecuteMutation($module, $props);
    }

    public function getComponentMutationResolverBridge(array $module): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOADACTION_SYSTEM_ACTIVATEPLUGINS:
                return $this->instanceManager->getInstance(ActivatePluginsMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($module);
    }
}



