<?php

use PoP\ComponentModel\Checkpoints\CheckpointInterface;

trait PoP_CommonUserRoles_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS,
                POP_COMMONUSERROLES_ROUTE_INDIVIDUALS,
                POP_COMMONUSERROLES_ROUTE_ADDPROFILEORGANIZATION,
                POP_COMMONUSERROLES_ROUTE_ADDPROFILEINDIVIDUAL,
                POP_COMMONUSERROLES_ROUTE_EDITPROFILEORGANIZATION,
                POP_COMMONUSERROLES_ROUTE_EDITPROFILEINDIVIDUAL,
            )
        );
    }

    /**
     * @return array<string,CheckpointInterface[]>
     */
    public function getRouteCheckpoints(): array
    {
        return array(
            POP_COMMONUSERROLES_ROUTE_ADDPROFILEORGANIZATION => [$this->getDoingPostUserNotLoggedInAggregateCheckpoint()],
            POP_COMMONUSERROLES_ROUTE_ADDPROFILEINDIVIDUAL => [$this->getDoingPostUserNotLoggedInAggregateCheckpoint()],
            POP_COMMONUSERROLES_ROUTE_EDITPROFILEORGANIZATION => POPCOMMONUSERROLES_CHECKPOINTCONFIGURATION_PROFILEORGANIZATION_DATAFROMSERVER,//PoP_CommonUserRoles_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPCOMMONUSERROLES_CHECKPOINTCONFIGURATION_PROFILEORGANIZATION_DATAFROMSERVER),
            POP_COMMONUSERROLES_ROUTE_EDITPROFILEINDIVIDUAL => POPCOMMONUSERROLES_CHECKPOINTCONFIGURATION_PROFILEINDIVIDUAL_DATAFROMSERVER,//PoP_CommonUserRoles_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPCOMMONUSERROLES_CHECKPOINTCONFIGURATION_PROFILEINDIVIDUAL_DATAFROMSERVER),
        );
    }
}
