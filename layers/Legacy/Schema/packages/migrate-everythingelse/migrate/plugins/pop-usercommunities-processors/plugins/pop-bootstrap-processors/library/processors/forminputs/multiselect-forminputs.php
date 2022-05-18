<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_ProfileMultiSelectFormInputs extends PoP_Module_Processor_MultiSelectFormInputsBase
{
    public final const MODULE_URE_FORMINPUT_MEMBERPRIVILEGES = 'ure-forminput-memberprivileges';
    public final const MODULE_URE_FORMINPUT_MEMBERTAGS = 'ure-forminput-membertags';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FORMINPUT_MEMBERPRIVILEGES],
            [self::class, self::MODULE_URE_FORMINPUT_MEMBERTAGS],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_FORMINPUT_MEMBERPRIVILEGES:
                return TranslationAPIFacade::getInstance()->__('Privileges', 'ure-popprocessors');

            case self::MODULE_URE_FORMINPUT_MEMBERTAGS:
                return TranslationAPIFacade::getInstance()->__('Tags', 'ure-popprocessors');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function getInputClass(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_FORMINPUT_MEMBERPRIVILEGES:
                return GD_URE_FormInput_MemberPrivileges::class;
            
            case self::MODULE_URE_FORMINPUT_MEMBERTAGS:
                return GD_URE_FormInput_MemberTags::class;
        }
        
        return parent::getInputClass($componentVariation);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_FORMINPUT_MEMBERPRIVILEGES:
                return 'memberprivileges';

            case self::MODULE_URE_FORMINPUT_MEMBERTAGS:
                return 'membertags';
        }
        
        return parent::getDbobjectField($componentVariation);
    }
}



