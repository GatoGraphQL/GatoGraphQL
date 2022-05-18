<?php

abstract class PoP_Module_Processor_CreateProfileDataloadsBase extends PoP_Module_Processor_CreateUserDataloadsBase
{
    protected function getFeedbackmessageModule(array $component)
    {
        return [PoP_Module_Processor_ProfileFeedbackMessages::class, PoP_Module_Processor_ProfileFeedbackMessages::MODULE_FEEDBACKMESSAGE_CREATEPROFILE];
    }
}
