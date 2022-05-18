<?php

class PoP_Module_Processor_ListFeedbackMessageInners extends PoP_Module_Processor_ListFeedbackMessageInnersBase
{
    public final const COMPONENT_FEEDBACKMESSAGEINNER_ITEMLIST = 'feedbackmessageinner-itemlist';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGEINNER_ITEMLIST],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $layouts = array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_ITEMLIST => [PoP_Module_Processor_DomainFeedbackMessageAlertLayouts::class, PoP_Module_Processor_DomainFeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_ITEMLIST],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



