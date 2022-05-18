<?php

class PoP_Module_Processor_CommentsFeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const MODULE_FEEDBACKMESSAGEINNER_ADDCOMMENT = 'feedbackmessageinner-addcomment';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGEINNER_ADDCOMMENT],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $layouts = array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_ADDCOMMENT => [PoP_Module_Processor_CommentsFeedbackMessageAlertLayouts::class, PoP_Module_Processor_CommentsFeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_ADDCOMMENT],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



