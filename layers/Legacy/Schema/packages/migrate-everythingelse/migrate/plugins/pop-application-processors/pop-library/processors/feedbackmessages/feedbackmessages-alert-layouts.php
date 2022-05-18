<?php

class PoP_Module_Processor_DomainFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_ITEMLIST = 'layout-feedbackmessagealert-itemlist';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_EMPTY = 'layout-feedbackmessagealert-empty';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_ITEMLIST],
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_EMPTY],
        );
    }

    public function getLayoutSubmodule(array $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_ITEMLIST => [PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST],
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_EMPTY => [PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_EMPTY],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($component);
    }
}



