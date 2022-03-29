<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdateUserTextareaFormInputs extends PoP_Module_Processor_TextareaFormInputsBase
{
    public const MODULE_FORMINPUT_CUU_DESCRIPTION = 'forminput-cuu-description';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_CUU_DESCRIPTION],
        );
    }

    public function getRows(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUU_DESCRIPTION:
                return 10;
        }

        return parent::getRows($module, $props);
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUU_DESCRIPTION:
                return TranslationAPIFacade::getInstance()->__('Description', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUU_DESCRIPTION:
                return 'description';
        }
        
        return parent::getDbobjectField($module);
    }
}



