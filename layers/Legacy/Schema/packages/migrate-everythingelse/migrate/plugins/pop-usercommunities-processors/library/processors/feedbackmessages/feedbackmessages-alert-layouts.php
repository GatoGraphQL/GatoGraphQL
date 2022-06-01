<?php

class GD_URE_Module_Processor_ProfileFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEMYCOMMUNITIES = 'layout-feedbackmessagealert-updatemycommunities';
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_INVITENEWMEMBERS = 'layout-feedbackmessagealert-invitemembers';
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_EDITMEMBERSHIP = 'layout-feedbackmessagealert-editmembership';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEMYCOMMUNITIES],
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_INVITENEWMEMBERS],
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_EDITMEMBERSHIP],
        );
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEMYCOMMUNITIES => [GD_URE_Module_Processor_ProfileFeedbackMessageLayouts::class, GD_URE_Module_Processor_ProfileFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES],
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_INVITENEWMEMBERS => [GD_URE_Module_Processor_ProfileFeedbackMessageLayouts::class, GD_URE_Module_Processor_ProfileFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_INVITENEWMEMBERS],
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_EDITMEMBERSHIP => [GD_URE_Module_Processor_ProfileFeedbackMessageLayouts::class, GD_URE_Module_Processor_ProfileFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_EDITMEMBERSHIP],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubcomponent($component);
    }
}



