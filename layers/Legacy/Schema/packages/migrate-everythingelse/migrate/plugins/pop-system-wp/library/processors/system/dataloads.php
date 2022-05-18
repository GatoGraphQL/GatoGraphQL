<?php
use PoP\ComponentModel\ComponentProcessors\AbstractDataloadComponentProcessor;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\ActivatePluginsMutationResolverBridge;

class PoP_SystemWP_WP_Module_Processor_SystemActions extends AbstractDataloadComponentProcessor
{
    public final const COMPONENT_DATALOADACTION_SYSTEM_ACTIVATEPLUGINS = 'dataloadaction-system-activateplugins';

    // use PoP_SystemWP_WP_Module_Processor_SystemActionsTrait;
    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOADACTION_SYSTEM_ACTIVATEPLUGINS],
        );
    }

    public function shouldExecuteMutation(array $component, array &$props): bool
    {

        // The actionexecution is triggered directly
        switch ($component[1]) {
            case self::COMPONENT_DATALOADACTION_SYSTEM_ACTIVATEPLUGINS:
                return true;
        }

        return parent::shouldExecuteMutation($component, $props);
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOADACTION_SYSTEM_ACTIVATEPLUGINS:
                return $this->instanceManager->getInstance(ActivatePluginsMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }
}



