<?php

class PoP_Module_Processor_CommentsFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_COMMENTS = 'layout-feedbackmessagealert-comments';
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_ADDCOMMENT = 'layout-feedbackmessagealert-addcomment';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_COMMENTS,
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_ADDCOMMENT,
        );
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_COMMENTS => [PoP_Module_Processor_CommentsFeedbackMessageLayouts::class, PoP_Module_Processor_CommentsFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_COMMENTS],
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_ADDCOMMENT => [PoP_Module_Processor_CommentsFeedbackMessageLayouts::class, PoP_Module_Processor_CommentsFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ADDCOMMENT],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubcomponent($component);
    }
}



