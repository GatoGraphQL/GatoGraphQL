<?php

class PoP_Module_Processor_CommentHiddenInputAlertFormComponents extends PoP_Module_Processor_CommentHiddenInputAlertFormComponentsBase
{
    public const MODULE_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENT = 'formcomponent-hiddeninputalert-layoutcomment';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENT],
        );
    }
    
    public function getHiddeninputModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_HIDDENINPUTALERT_LAYOUTCOMMENT:
                return [PoP_Module_Processor_HiddenInputFormInputs::class, PoP_Module_Processor_HiddenInputFormInputs::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENT];
        }

        return parent::getHiddeninputModule($module);
    }
}



