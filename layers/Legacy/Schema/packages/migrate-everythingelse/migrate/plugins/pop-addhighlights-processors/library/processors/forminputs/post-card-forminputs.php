<?php

class PoP_AddHighlights_Module_Processor_PostTriggerLayoutFormComponentValues extends PoP_Module_Processor_PostTriggerLayoutFormComponentValuesBase
{
    public final const MODULE_FORMCOMPONENT_CARD_HIGHLIGHTEDPOST = 'card-highlightedpost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENT_CARD_HIGHLIGHTEDPOST],
        );
    }

    public function getTriggerSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_CARD_HIGHLIGHTEDPOST:
                return [PoP_AddHighlights_Module_Processor_PostHiddenInputAlertFormComponents::class, PoP_AddHighlights_Module_Processor_PostHiddenInputAlertFormComponents::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_HIGHLIGHTEDPOST];
        }

        return parent::getTriggerSubmodule($component);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_CARD_HIGHLIGHTEDPOST:
                return 'highlightedpost';
        }

        return parent::getDbobjectField($component);
    }
}



