<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class Custom_URE_AAL_PoPProcessors_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public final const MODULE_UREAAL_BUTTON_EDITMEMBERSHIP = 'ure-aal-button-editmembership';
    public final const MODULE_UREAAL_BUTTON_VIEWALLMEMBERS = 'ure-aal-button-viewallmembers';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_UREAAL_BUTTON_EDITMEMBERSHIP],
            [self::class, self::MODULE_UREAAL_BUTTON_VIEWALLMEMBERS],
        );
    }

    public function getButtoninnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_UREAAL_BUTTON_EDITMEMBERSHIP:
                return [Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonInners::class, Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonInners::MODULE_UREAAL_BUTTONINNER_EDITMEMBERSHIP];

            case self::MODULE_UREAAL_BUTTON_VIEWALLMEMBERS:
                return [Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonInners::class, Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonInners::MODULE_UREAAL_BUTTONINNER_VIEWALLMEMBERS];
        }

        return parent::getButtoninnerSubmodule($componentVariation);
    }

    public function getUrlField(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_UREAAL_BUTTON_EDITMEMBERSHIP:
                return 'editUserMembershipURL';
        
            case self::MODULE_UREAAL_BUTTON_VIEWALLMEMBERS:
                return 'communityMembersURL';
        }

        return parent::getUrlField($componentVariation);
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_UREAAL_BUTTON_EDITMEMBERSHIP:
                return TranslationAPIFacade::getInstance()->__('Edit membership', 'poptheme-wassup');
        
            case self::MODULE_UREAAL_BUTTON_VIEWALLMEMBERS:
                return TranslationAPIFacade::getInstance()->__('View all members', 'poptheme-wassup');
        }
        
        return parent::getTitle($componentVariation, $props);
    }

    public function getLinktarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_UREAAL_BUTTON_EDITMEMBERSHIP:
                return POP_TARGET_ADDONS;
        }
        
        return parent::getLinktarget($componentVariation, $props);
    }

    public function getBtnClass(array $componentVariation, array &$props)
    {
        $ret = parent::getBtnClass($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_UREAAL_BUTTON_EDITMEMBERSHIP:
            case self::MODULE_UREAAL_BUTTON_VIEWALLMEMBERS:
                $ret .= ' btn btn-xs btn-link';
                break;
        }

        return $ret;
    }
}


