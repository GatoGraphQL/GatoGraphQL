<?php

use PoP\ComponentModel\Checkpoints\CheckpointInterface;

trait PoP_ContentPostLinksCreation_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK,
                POP_CONTENTPOSTLINKSCREATION_ROUTE_EDITCONTENTPOSTLINK,
                POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS,
            )
        );
    }

    public function needsTargetId()
    {
        return array(
            POP_CONTENTPOSTLINKSCREATION_ROUTE_EDITCONTENTPOSTLINK => true,
        );
    }

    /**
     * @return array<string,CheckpointInterface[]>
     */
    public function getRouteCheckpoints(): array
    {
        return array(
            POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK => [$this->getDoingPostUserLoggedInAggregateCheckpoint()],
            POP_CONTENTPOSTLINKSCREATION_ROUTE_EDITCONTENTPOSTLINK => POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT,
            POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS => [$this->getUserLoggedInCheckpoint()],
        );
    }
}
