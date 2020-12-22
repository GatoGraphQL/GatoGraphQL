<?php

class PoP_AddHighlights_Module_Processor_PostHiddenInputAlertFormComponents extends PoP_Module_Processor_PostHiddenInputAlertFormComponentsBase
{
    public const MODULE_FORMCOMPONENT_HIDDENINPUTALERT_HIGHLIGHTEDPOST = 'formcomponent-hiddeninputalert-highlightedpost';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_HIGHLIGHTEDPOST],
        );
    }
    
    public function getHiddeninputModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_HIGHLIGHTEDPOST:
                return [PoP_AddHighlights_Processor_HiddenInputFormInputs::class, PoP_AddHighlights_Processor_HiddenInputFormInputs::MODULE_FORMINPUT_HIDDENINPUT_HIGHLIGHTEDPOST];
        }

        return parent::getHiddeninputModule($module);
    }
}



