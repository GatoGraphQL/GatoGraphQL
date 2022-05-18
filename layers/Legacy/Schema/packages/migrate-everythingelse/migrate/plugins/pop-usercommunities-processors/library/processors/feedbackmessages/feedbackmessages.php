<?php

class GD_URE_Module_Processor_ProfileFeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const MODULE_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES = 'feedbackmessage-updatemycommunities';
    public final const MODULE_FEEDBACKMESSAGE_INVITENEWMEMBERS = 'feedbackmessage-invitemembers';
    public final const MODULE_FEEDBACKMESSAGE_EDITMEMBERSHIP = 'feedbackmessage-editmembership';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES],
            [self::class, self::MODULE_FEEDBACKMESSAGE_INVITENEWMEMBERS],
            [self::class, self::MODULE_FEEDBACKMESSAGE_EDITMEMBERSHIP],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::MODULE_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES => [GD_URE_Module_Processor_ProfileFeedbackMessageInners::class, GD_URE_Module_Processor_ProfileFeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_UPDATEMYCOMMUNITIES],
            self::MODULE_FEEDBACKMESSAGE_INVITENEWMEMBERS => [GD_URE_Module_Processor_ProfileFeedbackMessageInners::class, GD_URE_Module_Processor_ProfileFeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_INVITENEWMEMBERS],
            self::MODULE_FEEDBACKMESSAGE_EDITMEMBERSHIP => [GD_URE_Module_Processor_ProfileFeedbackMessageInners::class, GD_URE_Module_Processor_ProfileFeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_EDITMEMBERSHIP],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



