<?php

use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\CustomPosts\Types\Status;

abstract class PoP_Module_Processor_MySectionDataloadsBase extends PoP_Module_Processor_SectionDataloadsBase
{

    //-------------------------------------------------
    // PUBLIC Overriding Functions
    //-------------------------------------------------
    protected function getCheckpointmessageModule(array $component)
    {
        return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::COMPONENT_CHECKPOINTMESSAGE_LOGGEDIN];
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        // if ($this->requiresUserState($component)) {

        // When the block requires user state, make it reload itself when the user logs in/out
        // Important: execute these 2 functions in this order! 1st: delete params, 2nd: do the refetch
        $this->addJsmethod($ret, 'deleteBlockFeedbackValueOnUserLoggedInOut');
        $this->addJsmethod($ret, 'refetchBlockOnUserLoggedInOut');
        // }

        return $ret;
    }

    protected function getImmutableDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);

        // Any post status
        $ret['status'] = [
            Status::PUBLISHED,
            Status::DRAFT,
            Status::PENDING,
        ];

        return $ret;
    }

    protected function getMutableonrequestDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);

        // Logged-in author
        $ret['authors'] = [\PoP\Root\App::getState('current-user-id')];

        return $ret;
    }
}
