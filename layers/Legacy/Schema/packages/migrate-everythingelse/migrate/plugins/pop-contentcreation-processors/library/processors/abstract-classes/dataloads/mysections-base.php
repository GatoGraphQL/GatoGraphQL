<?php

use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\CustomPosts\Types\Status;

abstract class PoP_Module_Processor_MySectionDataloadsBase extends PoP_Module_Processor_SectionDataloadsBase
{

    //-------------------------------------------------
    // PUBLIC Overriding Functions
    //-------------------------------------------------
    protected function getCheckpointmessageModule(array $componentVariation)
    {
        return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_LOGGEDIN];
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        // if ($this->requiresUserState($componentVariation)) {

        // When the block requires user state, make it reload itself when the user logs in/out
        // Important: execute these 2 functions in this order! 1st: delete params, 2nd: do the refetch
        $this->addJsmethod($ret, 'deleteBlockFeedbackValueOnUserLoggedInOut');
        $this->addJsmethod($ret, 'refetchBlockOnUserLoggedInOut');
        // }

        return $ret;
    }

    protected function getImmutableDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($componentVariation, $props);

        // Any post status
        $ret['status'] = [
            Status::PUBLISHED,
            Status::DRAFT,
            Status::PENDING,
        ];

        return $ret;
    }

    protected function getMutableonrequestDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($componentVariation, $props);

        // Logged-in author
        $ret['authors'] = [\PoP\Root\App::getState('current-user-id')];

        return $ret;
    }
}
