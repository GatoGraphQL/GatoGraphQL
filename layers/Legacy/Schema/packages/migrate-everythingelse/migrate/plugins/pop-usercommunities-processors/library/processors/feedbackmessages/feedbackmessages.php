<?php

class GD_URE_Module_Processor_ProfileFeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public const MODULE_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES = 'feedbackmessage-updatemycommunities';
    public const MODULE_FEEDBACKMESSAGE_INVITENEWMEMBERS = 'feedbackmessage-invitemembers';
    public const MODULE_FEEDBACKMESSAGE_EDITMEMBERSHIP = 'feedbackmessage-editmembership';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES],
            [self::class, self::MODULE_FEEDBACKMESSAGE_INVITENEWMEMBERS],
            [self::class, self::MODULE_FEEDBACKMESSAGE_EDITMEMBERSHIP],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES => [GD_URE_Module_Processor_ProfileFeedbackMessageInners::class, GD_URE_Module_Processor_ProfileFeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_UPDATEMYCOMMUNITIES],
            self::MODULE_FEEDBACKMESSAGE_INVITENEWMEMBERS => [GD_URE_Module_Processor_ProfileFeedbackMessageInners::class, GD_URE_Module_Processor_ProfileFeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_INVITENEWMEMBERS],
            self::MODULE_FEEDBACKMESSAGE_EDITMEMBERSHIP => [GD_URE_Module_Processor_ProfileFeedbackMessageInners::class, GD_URE_Module_Processor_ProfileFeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_EDITMEMBERSHIP],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



