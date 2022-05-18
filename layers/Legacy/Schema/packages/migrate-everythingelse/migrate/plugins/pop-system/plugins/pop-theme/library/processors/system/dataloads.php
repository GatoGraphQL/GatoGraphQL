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

    public function shouldExecuteMutation(array $module, array &$props): bool
    {

        // The actionexecution is triggered directly
        switch ($module[1]) {
            case self::MODULE_DATALOADACTION_SYSTEM_GENERATETHEME:
                return true;
        }

        return parent::shouldExecuteMutation($module, $props);
    }

    public function getComponentMutationResolverBridge(array $module): ?\PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOADACTION_SYSTEM_GENERATETHEME:
                return $this->instanceManager->getInstance(GenerateThemeMutationResolverBridge::class);
        }

        return parent::getComponentMutationResolverBridge($module);
    }
}



