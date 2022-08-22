<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_CommonUserRoles_UserCommunities_Module_Processor_SelectFormInputs extends PoP_Module_Processor_BooleanSelectFormInputsBase
{
    public final const COMPONENT_URE_FORMINPUT_CUP_ISCOMMUNITY = 'ure-cup-iscommunity';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_URE_FORMINPUT_CUP_ISCOMMUNITY,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_FORMINPUT_CUP_ISCOMMUNITY:
                return TranslationAPIFacade::getInstance()->__('Does your organization accept members?', 'ure-popprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getInputClass(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_URE_FORMINPUT_CUP_ISCOMMUNITY:
                return GD_FormInput_YesNo::class;
        }
        
        return parent::getInputClass($component);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_URE_FORMINPUT_CUP_ISCOMMUNITY:
                return 'isCommunity';
        }
        
        return parent::getDbobjectField($component);
    }
}



