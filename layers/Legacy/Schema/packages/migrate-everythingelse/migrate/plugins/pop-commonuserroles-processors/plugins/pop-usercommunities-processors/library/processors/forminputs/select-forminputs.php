<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_CommonUserRoles_UserCommunities_Module_Processor_SelectFormInputs extends PoP_Module_Processor_BooleanSelectFormInputsBase
{
    public final const MODULE_URE_FORMINPUT_CUP_ISCOMMUNITY = 'ure-cup-iscommunity';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_FORMINPUT_CUP_ISCOMMUNITY],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMINPUT_CUP_ISCOMMUNITY:
                return TranslationAPIFacade::getInstance()->__('Does your organization accept members?', 'ure-popprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMINPUT_CUP_ISCOMMUNITY:
                return GD_FormInput_YesNo::class;
        }
        
        return parent::getInputClass($component);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMINPUT_CUP_ISCOMMUNITY:
                return 'isCommunity';
        }
        
        return parent::getDbobjectField($component);
    }
}



