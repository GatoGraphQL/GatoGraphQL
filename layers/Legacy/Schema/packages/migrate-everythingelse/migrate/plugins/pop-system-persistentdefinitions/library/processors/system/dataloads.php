<?php
use PoP\ComponentModel\ComponentProcessors\AbstractDataloadComponentProcessor;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\SaveDefinitionFileMutationResolverBridge;

class PoP_PersistentDefinitionsSystem_Module_Processor_SystemActions extends AbstractDataloadComponentProcessor
{
    public final const MODULE_DATALOADACTION_SYSTEM_SAVEDEFINITIONFILE = 'dataloadaction-system-savedefinitionfile';

    // use PoP_PersistentDefinitionsSystem_Module_Processor_SystemActionsTrait;
    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOADACTION_SYSTEM_SAVEDEFINITIONFILE],
        );
    }

    public function shouldExecuteMutation(array $componentVariation, array &$props): bool
    {
        // The actionexecution is triggered directly
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOADACTION_SYSTEM_SAVEDEFINITIONFILE:
                return true;
        }

        return parent::shouldExecuteMutation($componentVariation, $props);
    }

    public function getComponentMutationResolverBridge(array $componentVariation): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOADACTION_SYSTEM_SAVEDEFINITIONFILE:
                return $this->instanceManager->getInstance(SaveDefinitionFileMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($componentVariation);
    }
}



