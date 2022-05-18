<?php
use PoP\ComponentModel\ComponentProcessors\AbstractDataloadComponentProcessor;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\GenerateThemeMutationResolverBridge;

class PoP_System_Theme_Module_Processor_SystemActions extends AbstractDataloadComponentProcessor
{
    public final const MODULE_DATALOADACTION_SYSTEM_GENERATETHEME = 'dataloadaction-system-generate-theme';

    // use PoP_System_Theme_Module_Processor_SystemActionsTrait;
    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOADACTION_SYSTEM_GENERATETHEME],
        );
    }

    public function shouldExecuteMutation(array $componentVariation, array &$props): bool
    {

        // The actionexecution is triggered directly
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOADACTION_SYSTEM_GENERATETHEME:
                return true;
        }

        return parent::shouldExecuteMutation($componentVariation, $props);
    }

    public function getComponentMutationResolverBridge(array $componentVariation): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOADACTION_SYSTEM_GENERATETHEME:
                return $this->instanceManager->getInstance(GenerateThemeMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($componentVariation);
    }
}



