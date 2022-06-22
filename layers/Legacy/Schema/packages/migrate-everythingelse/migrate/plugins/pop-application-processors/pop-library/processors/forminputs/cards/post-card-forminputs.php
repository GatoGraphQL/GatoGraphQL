<?php

class PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues extends PoP_Module_Processor_PostTriggerLayoutFormComponentValuesBase
{
    public final const COMPONENT_FORMCOMPONENT_CARD_POST = 'forminput-post-card';
    public final const COMPONENT_FORMCOMPONENT_CARD_COMMENTPOST = 'forminput-commentpost-card';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMCOMPONENT_CARD_POST,
            self::COMPONENT_FORMCOMPONENT_CARD_COMMENTPOST,
        );
    }

    public function getTriggerSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_CARD_POST:
                return [PoP_Module_Processor_PostHiddenInputAlertFormComponents::class, PoP_Module_Processor_PostHiddenInputAlertFormComponents::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTPOST];

            case self::COMPONENT_FORMCOMPONENT_CARD_COMMENTPOST:
                return [PoP_Module_Processor_PostHiddenInputAlertFormComponents::class, PoP_Module_Processor_PostHiddenInputAlertFormComponents::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENTPOST];
        }

        return parent::getTriggerSubcomponent($component);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_CARD_POST:
                return 'self';

            case self::COMPONENT_FORMCOMPONENT_CARD_COMMENTPOST:
                return 'customPost';
        }

        return parent::getDbobjectField($component);
    }
}



