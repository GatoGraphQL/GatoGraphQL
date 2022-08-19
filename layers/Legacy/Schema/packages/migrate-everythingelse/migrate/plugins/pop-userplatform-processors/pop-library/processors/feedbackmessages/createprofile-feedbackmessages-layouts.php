<?php

class PoP_Module_Processor_CreateProfileFeedbackMessageLayouts extends PoP_Module_Processor_CreateUserFormMesageFeedbackLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATEPROFILE = 'layout-feedbackmessage-createprofile';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATEPROFILE,
        );
    }
}



