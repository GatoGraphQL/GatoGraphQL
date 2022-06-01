<?php

class PoP_AddHighlights_Module_Processor_PostTriggerLayoutFormComponentValues extends PoP_Module_Processor_PostTriggerLayoutFormComponentValuesBase
{
    public final const COMPONENT_FORMCOMPONENT_CARD_HIGHLIGHTEDPOST = 'card-highlightedpost';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMCOMPONENT_CARD_HIGHLIGHTEDPOST,
        );
    }

    public function getTriggerSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_CARD_HIGHLIGHTEDPOST:
                return [PoP_AddHighlights_Module_Processor_PostHiddenInputAlertFormComponents::class, PoP_AddHighlights_Module_Processor_PostHiddenInputAlertFormComponents::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_HIGHLIGHTEDPOST];
        }

        return parent::getTriggerSubcomponent($component);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_CARD_HIGHLIGHTEDPOST:
                return 'highlightedpost';
        }

        return parent::getDbobjectField($component);
    }
}



