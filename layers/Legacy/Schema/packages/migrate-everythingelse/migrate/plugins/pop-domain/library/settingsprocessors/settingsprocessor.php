<?php

use PoP\ComponentModel\Checkpoints\CheckpointInterface;

class PoPTheme_Wassup_Domain_Module_SettingsProcessor extends PoP_WebPlatformEngine_Module_SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN,
            )
        );
    }

    public function silentDocument()
    {
        return array(
            POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN => true,
        );
    }

    public function isAppshell()
    {
        return array(
            POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN => true,
        );
    }

    public function storeLocal()
    {
        return array(
            POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN => true,
        );
    }

    /**
     * @return array<string,CheckpointInterface[]>
     */
    public function getRouteCheckpoints(): array
    {
        return array(
            POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN => POPMULTIDOMAIN_CHECKPOINTCONFIGURATION_DOMAINVALID,//PoPMultiDomain_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPMULTIDOMAIN_CHECKPOINTCONFIGURATION_DOMAINVALID),//$profile_datafromserver,
        );
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_Domain_Module_SettingsProcessor();
