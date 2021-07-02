<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_URE_Module_Processor_SelectFormInputs extends PoP_Module_Processor_SelectFormInputsBase
{
    public const MODULE_URE_FORMINPUT_MEMBERSTATUS = 'ure-forminput-memberstatus';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FORMINPUT_MEMBERSTATUS],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMINPUT_MEMBERSTATUS:
                return TranslationAPIFacade::getInstance()->__('Status', 'ure-popprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function isMandatory(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMINPUT_MEMBERSTATUS:
                return true;
        }
        
        return parent::isMandatory($module, $props);
    }

    public function getInputClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMINPUT_MEMBERSTATUS:
                return GD_URE_FormInput_MemberStatus::class;
        }
        
        return parent::getInputClass($module);
    }

    public function getDbobjectField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMINPUT_MEMBERSTATUS:
                return 'memberstatus';
        }
        
        return parent::getDbobjectField($module);
    }
}



