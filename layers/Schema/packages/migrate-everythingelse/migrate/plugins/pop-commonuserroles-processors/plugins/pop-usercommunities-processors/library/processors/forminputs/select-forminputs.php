<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_CommonUserRoles_UserCommunities_Module_Processor_SelectFormInputs extends PoP_Module_Processor_BooleanSelectFormInputsBase
{
    public const MODULE_URE_FORMINPUT_CUP_ISCOMMUNITY = 'ure-cup-iscommunity';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FORMINPUT_CUP_ISCOMMUNITY],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMINPUT_CUP_ISCOMMUNITY:
                return TranslationAPIFacade::getInstance()->__('Does your organization accept members?', 'ure-popprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMINPUT_CUP_ISCOMMUNITY:
                return GD_FormInput_YesNo::class;
        }
        
        return parent::getInputClass($module);
    }

    public function getDbobjectField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMINPUT_CUP_ISCOMMUNITY:
                return 'isCommunity';
        }
        
        return parent::getDbobjectField($module);
    }
}



