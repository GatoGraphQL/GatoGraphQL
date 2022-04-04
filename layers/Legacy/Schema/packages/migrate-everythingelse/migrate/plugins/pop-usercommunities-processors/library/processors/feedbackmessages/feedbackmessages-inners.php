<?php

class GD_URE_Module_Processor_ProfileFeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const MODULE_FEEDBACKMESSAGEINNER_UPDATEMYCOMMUNITIES = 'feedbackmessageinner-updatemycommunities';
    public final const MODULE_FEEDBACKMESSAGEINNER_INVITENEWMEMBERS = 'feedbackmessageinner-invitemembers';
    public final const MODULE_FEEDBACKMESSAGEINNER_EDITMEMBERSHIP = 'feedbackmessageinner-editmembership';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_UPDATEMYCOMMUNITIES],
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_INVITENEWMEMBERS],
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_EDITMEMBERSHIP],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_FEEDBACKMESSAGEINNER_UPDATEMYCOMMUNITIES => [GD_URE_Module_Processor_ProfileFeedbackMessageAlertLayouts::class, GD_URE_Module_Processor_ProfileFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEMYCOMMUNITIES],
            self::MODULE_FEEDBACKMESSAGEINNER_INVITENEWMEMBERS => [GD_URE_Module_Processor_ProfileFeedbackMessageAlertLayouts::class, GD_URE_Module_Processor_ProfileFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_INVITENEWMEMBERS],
            self::MODULE_FEEDBACKMESSAGEINNER_EDITMEMBERSHIP => [GD_URE_Module_Processor_ProfileFeedbackMessageAlertLayouts::class, GD_URE_Module_Processor_ProfileFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_EDITMEMBERSHIP],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



