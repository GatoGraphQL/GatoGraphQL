<?php

class PoP_Module_Processor_CreateProfileFeedbackMessageLayouts extends PoP_Module_Processor_CreateUserFormMesageFeedbackLayoutsBase
{
    public const MODULE_LAYOUT_FEEDBACKMESSAGE_CREATEPROFILE = 'layout-feedbackmessage-createprofile';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_CREATEPROFILE],
        );
    }
}



