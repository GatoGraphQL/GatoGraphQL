<?php

class GD_URE_Module_Processor_ProfileFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEMYCOMMUNITIES = 'layout-feedbackmessagealert-updatemycommunities';
    public const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_INVITENEWMEMBERS = 'layout-feedbackmessagealert-invitemembers';
    public const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_EDITMEMBERSHIP = 'layout-feedbackmessagealert-editmembership';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEMYCOMMUNITIES],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_INVITENEWMEMBERS],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_EDITMEMBERSHIP],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        $layouts = array(
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEMYCOMMUNITIES => [GD_URE_Module_Processor_ProfileFeedbackMessageLayouts::class, GD_URE_Module_Processor_ProfileFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES],
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_INVITENEWMEMBERS => [GD_URE_Module_Processor_ProfileFeedbackMessageLayouts::class, GD_URE_Module_Processor_ProfileFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_INVITENEWMEMBERS],
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_EDITMEMBERSHIP => [GD_URE_Module_Processor_ProfileFeedbackMessageLayouts::class, GD_URE_Module_Processor_ProfileFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_EDITMEMBERSHIP],
        );

        if ($layout = $layouts[$module[1]]) {
            return $layout;
        }

        return parent::getLayoutSubmodule($module);
    }
}



