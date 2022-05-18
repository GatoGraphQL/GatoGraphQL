<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_CommonUserRoles_UserCommunities_Module_Processor_SelectFormInputs extends PoP_Module_Processor_BooleanSelectFormInputsBase
{
    public final const MODULE_URE_FORMINPUT_CUP_ISCOMMUNITY = 'ure-cup-iscommunity';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FORMINPUT_CUP_ISCOMMUNITY],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_FORMINPUT_CUP_ISCOMMUNITY:
                return TranslationAPIFacade::getInstance()->__('Does your organization accept members?', 'ure-popprocessors');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function getInputClass(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_FORMINPUT_CUP_ISCOMMUNITY:
                return GD_FormInput_YesNo::class;
        }
        
        return parent::getInputClass($componentVariation);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_FORMINPUT_CUP_ISCOMMUNITY:
                return 'isCommunity';
        }
        
        return parent::getDbobjectField($componentVariation);
    }
}



