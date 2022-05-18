<?php

class GD_URE_Module_Processor_ProfileFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEMYCOMMUNITIES = 'layout-feedbackmessagealert-updatemycommunities';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_INVITENEWMEMBERS = 'layout-feedbackmessagealert-invitemembers';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_EDITMEMBERSHIP = 'layout-feedbackmessagealert-editmembership';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEMYCOMMUNITIES],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_INVITENEWMEMBERS],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_EDITMEMBERSHIP],
        );
    }

    public function getLayoutSubmodule(array $componentVariation)
    {
        $layouts = array(
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEMYCOMMUNITIES => [GD_URE_Module_Processor_ProfileFeedbackMessageLayouts::class, GD_URE_Module_Processor_ProfileFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES],
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_INVITENEWMEMBERS => [GD_URE_Module_Processor_ProfileFeedbackMessageLayouts::class, GD_URE_Module_Processor_ProfileFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_INVITENEWMEMBERS],
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_EDITMEMBERSHIP => [GD_URE_Module_Processor_ProfileFeedbackMessageLayouts::class, GD_URE_Module_Processor_ProfileFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_EDITMEMBERSHIP],
        );

        if ($layout = $layouts[$componentVariation[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($componentVariation);
    }
}



