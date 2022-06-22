<?php

class PoP_Module_Processor_ListCommentsFeedbackMessageInners extends PoP_Module_Processor_ListFeedbackMessageInnersBase
{
    public final const COMPONENT_FEEDBACKMESSAGEINNER_COMMENTS = 'feedbackmessageinner-comments';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_COMMENTS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_COMMENTS => [PoP_Module_Processor_CommentsFeedbackMessageAlertLayouts::class, PoP_Module_Processor_CommentsFeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_COMMENTS],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



