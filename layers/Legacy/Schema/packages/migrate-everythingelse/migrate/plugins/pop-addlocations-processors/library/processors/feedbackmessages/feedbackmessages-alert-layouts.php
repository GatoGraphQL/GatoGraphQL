<?php

class PoP_Module_Processor_CreateLocationFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CREATELOCATION = 'layout-feedbackmessagealert-createlocation';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CREATELOCATION,
        );
    }

    public function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CREATELOCATION => [PoP_Module_Processor_CreateLocationFeedbackMessageLayouts::class, PoP_Module_Processor_CreateLocationFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATELOCATION],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubcomponent($component);
    }
}



