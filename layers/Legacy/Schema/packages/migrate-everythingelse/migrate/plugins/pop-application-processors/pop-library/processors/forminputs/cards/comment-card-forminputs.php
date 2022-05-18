<?php

class PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues extends PoP_Module_Processor_CommentTriggerLayoutFormComponentValuesBase
{
    public final const MODULE_FORMCOMPONENT_CARD_COMMENT = 'forminput-comment-card';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_CARD_COMMENT],
        );
    }

    public function getTriggerSubmodule(array $componentVariation): ?array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_CARD_COMMENT:
                return [PoP_Module_Processor_CommentHiddenInputAlertFormComponents::class, PoP_Module_Processor_CommentHiddenInputAlertFormComponents::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENT];
        }

        return parent::getTriggerSubmodule($componentVariation);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_CARD_COMMENT:
                return 'self';
        }

        return parent::getDbobjectField($componentVariation);
    }
}



