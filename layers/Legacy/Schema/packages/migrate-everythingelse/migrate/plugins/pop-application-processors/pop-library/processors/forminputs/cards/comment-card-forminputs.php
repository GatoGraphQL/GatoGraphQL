<?php

class PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues extends PoP_Module_Processor_CommentTriggerLayoutFormComponentValuesBase
{
    public final const COMPONENT_FORMCOMPONENT_CARD_COMMENT = 'forminput-comment-card';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENT_CARD_COMMENT],
        );
    }

    public function getTriggerSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_CARD_COMMENT:
                return [PoP_Module_Processor_CommentHiddenInputAlertFormComponents::class, PoP_Module_Processor_CommentHiddenInputAlertFormComponents::COMPONENT_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENT];
        }

        return parent::getTriggerSubcomponent($component);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_CARD_COMMENT:
                return 'self';
        }

        return parent::getDbobjectField($component);
    }
}



