<?php
use PoP\ComponentModel\ComponentProcessors\AbstractDataloadComponentProcessor;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\SaveDefinitionFileMutationResolverBridge;

class PoP_PersistentDefinitionsSystem_Module_Processor_SystemActions extends AbstractDataloadComponentProcessor
{
    public final const COMPONENT_DATALOADACTION_SYSTEM_SAVEDEFINITIONFILE = 'dataloadaction-system-savedefinitionfile';

    // use PoP_PersistentDefinitionsSystem_Module_Processor_SystemActionsTrait;
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOADACTION_SYSTEM_SAVEDEFINITIONFILE,
        );
    }

    public function shouldExecuteMutation(\PoP\ComponentModel\Component\Component $component, array &$props): bool
    {
        // The actionexecution is triggered directly
        switch ($component->name) {
            case self::COMPONENT_DATALOADACTION_SYSTEM_SAVEDEFINITIONFILE:
                return true;
        }

        return parent::shouldExecuteMutation($component, $props);
    }

    public function getComponentMutationResolverBridge(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOADACTION_SYSTEM_SAVEDEFINITIONFILE:
                return $this->instanceManager->getInstance(SaveDefinitionFileMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($component);
    }
}



