<?php

abstract class PoP_Module_Processor_CreateProfileDataloadsBase extends PoP_Module_Processor_CreateUserDataloadsBase
{
    protected function getFeedbackMessageComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_ProfileFeedbackMessages::class, PoP_Module_Processor_ProfileFeedbackMessages::COMPONENT_FEEDBACKMESSAGE_CREATEPROFILE];
    }
}
