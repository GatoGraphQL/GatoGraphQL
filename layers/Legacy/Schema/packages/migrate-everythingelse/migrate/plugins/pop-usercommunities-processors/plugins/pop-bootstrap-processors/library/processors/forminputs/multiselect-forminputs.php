<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_ProfileMultiSelectFormInputs extends PoP_Module_Processor_MultiSelectFormInputsBase
{
    public const MODULE_URE_FORMINPUT_MEMBERPRIVILEGES = 'ure-forminput-memberprivileges';
    public const MODULE_URE_FORMINPUT_MEMBERTAGS = 'ure-forminput-membertags';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FORMINPUT_MEMBERPRIVILEGES],
            [self::class, self::MODULE_URE_FORMINPUT_MEMBERTAGS],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMINPUT_MEMBERPRIVILEGES:
                return TranslationAPIFacade::getInstance()->__('Privileges', 'ure-popprocessors');

            case self::MODULE_URE_FORMINPUT_MEMBERTAGS:
                return TranslationAPIFacade::getInstance()->__('Tags', 'ure-popprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMINPUT_MEMBERPRIVILEGES:
                return GD_URE_FormInput_MemberPrivileges::class;
            
            case self::MODULE_URE_FORMINPUT_MEMBERTAGS:
                return GD_URE_FormInput_MemberTags::class;
        }
        
        return parent::getInputClass($module);
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMINPUT_MEMBERPRIVILEGES:
                return 'memberprivileges';

            case self::MODULE_URE_FORMINPUT_MEMBERTAGS:
                return 'membertags';
        }
        
        return parent::getDbobjectField($module);
    }
}



