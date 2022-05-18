<?php

class PoP_AddHighlights_Module_Processor_PostHiddenInputAlertFormComponents extends PoP_Module_Processor_PostHiddenInputAlertFormComponentsBase
{
    public final const MODULE_FORMCOMPONENT_HIDDENINPUTALERT_HIGHLIGHTEDPOST = 'formcomponent-hiddeninputalert-highlightedpost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_HIGHLIGHTEDPOST],
        );
    }
    
    public function getHiddeninputModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_HIGHLIGHTEDPOST:
                return [PoP_AddHighlights_Processor_HiddenInputFormInputs::class, PoP_AddHighlights_Processor_HiddenInputFormInputs::COMPONENT_FORMINPUT_HIDDENINPUT_HIGHLIGHTEDPOST];
        }

        return parent::getHiddeninputModule($component);
    }
}



