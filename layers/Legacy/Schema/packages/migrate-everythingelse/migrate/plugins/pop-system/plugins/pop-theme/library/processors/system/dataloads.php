<?php
use PoP\ComponentModel\ComponentProcessors\AbstractDataloadComponentProcessor;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\GenerateThemeMutationResolverBridge;

class PoP_System_Theme_Module_Processor_SystemActions extends AbstractDataloadComponentProcessor
{
    public final const MODULE_DATALOADACTION_SYSTEM_GENERATETHEME = 'dataloadaction-system-generate-theme';

    // use PoP_System_Theme_Module_Processor_SystemActionsTrait;
    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOADACTION_SYSTEM_GENERATETHEME],
        );
    }

    public function shouldExecuteMutation(array $component, array &$props): bool
    {

        // The actionexecution is triggered directly
        switch ($component[1]) {
            case self::COMPONENT_DATALOADACTION_SYSTEM_GENERATETHEME:
                return true;
        }

        return parent::shouldExecuteMutation($component, $props);
    }

    public function getComponentMutationResolverBridge(array $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOADACTION_SYSTEM_GENERATETHEME:
                return $this->instanceManager->getInstance(GenerateThemeMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }
}



