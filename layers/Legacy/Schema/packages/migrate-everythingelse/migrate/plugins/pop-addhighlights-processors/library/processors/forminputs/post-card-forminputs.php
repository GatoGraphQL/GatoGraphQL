<?php

class PoP_AddHighlights_Module_Processor_PostTriggerLayoutFormComponentValues extends PoP_Module_Processor_PostTriggerLayoutFormComponentValuesBase
{
    public final const MODULE_FORMCOMPONENT_CARD_HIGHLIGHTEDPOST = 'card-highlightedpost';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_CARD_HIGHLIGHTEDPOST],
        );
    }

    public function getTriggerSubmodule(array $componentVariation): ?array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_CARD_HIGHLIGHTEDPOST:
                return [PoP_AddHighlights_Module_Processor_PostHiddenInputAlertFormComponents::class, PoP_AddHighlights_Module_Processor_PostHiddenInputAlertFormComponents::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_HIGHLIGHTEDPOST];
        }

        return parent::getTriggerSubmodule($componentVariation);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_CARD_HIGHLIGHTEDPOST:
                return 'highlightedpost';
        }

        return parent::getDbobjectField($componentVariation);
    }
}



