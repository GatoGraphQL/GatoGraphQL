<?php

class PoP_SocialNetwork_Module_Processor_FeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CONTACTUSER = 'layout-feedbackmessagealert-contactuser';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CONTACTUSER],
        );
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CONTACTUSER => [PoP_SocialNetwork_Module_Processor_FeedbackMessageLayouts::class, PoP_SocialNetwork_Module_Processor_FeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CONTACTUSER],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubcomponent($component);
    }
}



