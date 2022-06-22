<?php

class PoP_Module_Processor_UserFeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const COMPONENT_FEEDBACKMESSAGEINNER_MYPREFERENCES = 'feedbackmessageinner-mypreferences';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_MYPREFERENCES,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_MYPREFERENCES => [PoP_Module_Processor_UserFeedbackMessageAlertLayouts::class, PoP_Module_Processor_UserFeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_MYPREFERENCES],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



