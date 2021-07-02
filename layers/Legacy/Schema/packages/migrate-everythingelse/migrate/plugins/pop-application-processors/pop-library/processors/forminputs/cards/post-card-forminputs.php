<?php

class PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues extends PoP_Module_Processor_PostTriggerLayoutFormComponentValuesBase
{
    public const MODULE_FORMCOMPONENT_CARD_POST = 'forminput-post-card';
    public const MODULE_FORMCOMPONENT_CARD_COMMENTPOST = 'forminput-commentpost-card';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_CARD_POST],
            [self::class, self::MODULE_FORMCOMPONENT_CARD_COMMENTPOST],
        );
    }

    public function getTriggerSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_CARD_POST:
                return [PoP_Module_Processor_PostHiddenInputAlertFormComponents::class, PoP_Module_Processor_PostHiddenInputAlertFormComponents::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTPOST];

            case self::MODULE_FORMCOMPONENT_CARD_COMMENTPOST:
                return [PoP_Module_Processor_PostHiddenInputAlertFormComponents::class, PoP_Module_Processor_PostHiddenInputAlertFormComponents::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENTPOST];
        }

        return parent::getTriggerSubmodule($module);
    }

    public function getDbobjectField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_CARD_POST:
                return 'self';

            case self::MODULE_FORMCOMPONENT_CARD_COMMENTPOST:
                return 'customPost';
        }

        return parent::getDbobjectField($module);
    }
}



