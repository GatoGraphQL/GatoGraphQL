<?php

class PoP_Module_Processor_ProfileFeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const COMPONENT_FEEDBACKMESSAGEINNER_CREATEPROFILE = 'feedbackmessageinner-createprofile';
    public final const COMPONENT_FEEDBACKMESSAGEINNER_UPDATEPROFILE = 'feedbackmessageinner-updateprofile';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_CREATEPROFILE,
            self::COMPONENT_FEEDBACKMESSAGEINNER_UPDATEPROFILE,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_CREATEPROFILE => [PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts::class, PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CREATEPROFILE],
            self::COMPONENT_FEEDBACKMESSAGEINNER_UPDATEPROFILE => [PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts::class, PoP_Module_Processor_ProfileFeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_UPDATEPROFILE],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



