<?php

class PoP_Module_Processor_PostHiddenInputAlertFormComponents extends PoP_Module_Processor_PostHiddenInputAlertFormComponentsBase
{
    public final const MODULE_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTPOST = 'formcomponent-hiddeninputalert-layoutpost';
    public final const MODULE_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENTPOST = 'formcomponent-hiddeninputalert-layoutcommentpost';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTPOST],
            [self::class, self::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENTPOST],
        );
    }
    
    public function getHiddeninputModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTPOST:
                return [PoP_Module_Processor_HiddenInputFormInputs::class, PoP_Module_Processor_HiddenInputFormInputs::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTPOST];

            case self::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENTPOST:
                return [PoP_Module_Processor_HiddenInputFormInputs::class, PoP_Module_Processor_HiddenInputFormInputs::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENTPOST];
        }

        return parent::getHiddeninputModule($module);
    }
}



