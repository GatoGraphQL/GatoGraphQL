<?php

class PoP_Module_Processor_CreateProfileFeedbackMessageLayouts extends PoP_Module_Processor_CreateUserFormMesageFeedbackLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGE_CREATEPROFILE = 'layout-feedbackmessage-createprofile';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATEPROFILE],
        );
    }
}



