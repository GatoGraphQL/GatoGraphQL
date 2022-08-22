<?php

class PoP_Module_Processor_UserFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_MYPREFERENCES = 'layout-feedbackmessagealert-mypreferences';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_MYPREFERENCES,
        );
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_MYPREFERENCES => [PoP_Module_Processor_UserFeedbackMessageLayouts::class, PoP_Module_Processor_UserFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_MYPREFERENCES],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubcomponent($component);
    }
}



