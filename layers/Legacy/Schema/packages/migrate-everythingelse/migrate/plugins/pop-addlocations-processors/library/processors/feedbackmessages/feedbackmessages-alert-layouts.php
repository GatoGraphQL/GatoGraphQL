<?php

class PoP_Module_Processor_CreateLocationFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CREATELOCATION = 'layout-feedbackmessagealert-createlocation';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CREATELOCATION],
        );
    }

    public function getLayoutSubcomponent(array $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CREATELOCATION => [PoP_Module_Processor_CreateLocationFeedbackMessageLayouts::class, PoP_Module_Processor_CreateLocationFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATELOCATION],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubcomponent($component);
    }
}



