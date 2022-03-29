<?php

class PoP_Application_Module_Processor_CommentTriggerLayoutFormComponentValues extends PoP_Module_Processor_CommentTriggerLayoutFormComponentValuesBase
{
    public const MODULE_FORMCOMPONENT_CARD_COMMENT = 'forminput-comment-card';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_CARD_COMMENT],
        );
    }

    public function getTriggerSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_CARD_COMMENT:
                return [PoP_Module_Processor_CommentHiddenInputAlertFormComponents::class, PoP_Module_Processor_CommentHiddenInputAlertFormComponents::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENT];
        }

        return parent::getTriggerSubmodule($module);
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_CARD_COMMENT:
                return 'self';
        }

        return parent::getDbobjectField($module);
    }
}



