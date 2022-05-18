<?php

abstract class PoP_Module_Processor_UpdateProfileDataloadsBase extends PoP_Module_Processor_UpdateUserDataloadsBase
{
    protected function getFeedbackmessageModule(array $componentVariation)
    {
        return [PoP_Module_Processor_ProfileFeedbackMessages::class, PoP_Module_Processor_ProfileFeedbackMessages::MODULE_FEEDBACKMESSAGE_UPDATEPROFILE];
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        $this->addJsmethod($ret, 'destroyPageOnUserLoggedOut');
        $this->addJsmethod($ret, 'refetchBlockOnUserLoggedIn');

        return $ret;
    }
}
