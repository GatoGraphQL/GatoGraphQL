<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class Custom_URE_AAL_PoPProcessors_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public final const COMPONENT_UREAAL_BUTTON_EDITMEMBERSHIP = 'ure-aal-button-editmembership';
    public final const COMPONENT_UREAAL_BUTTON_VIEWALLMEMBERS = 'ure-aal-button-viewallmembers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_UREAAL_BUTTON_EDITMEMBERSHIP],
            [self::class, self::COMPONENT_UREAAL_BUTTON_VIEWALLMEMBERS],
        );
    }

    public function getButtoninnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_UREAAL_BUTTON_EDITMEMBERSHIP:
                return [Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonInners::class, Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonInners::COMPONENT_UREAAL_BUTTONINNER_EDITMEMBERSHIP];

            case self::COMPONENT_UREAAL_BUTTON_VIEWALLMEMBERS:
                return [Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonInners::class, Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonInners::COMPONENT_UREAAL_BUTTONINNER_VIEWALLMEMBERS];
        }

        return parent::getButtoninnerSubcomponent($component);
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_UREAAL_BUTTON_EDITMEMBERSHIP:
                return 'editUserMembershipURL';
        
            case self::COMPONENT_UREAAL_BUTTON_VIEWALLMEMBERS:
                return 'communityMembersURL';
        }

        return parent::getUrlField($component);
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_UREAAL_BUTTON_EDITMEMBERSHIP:
                return TranslationAPIFacade::getInstance()->__('Edit membership', 'poptheme-wassup');
        
            case self::COMPONENT_UREAAL_BUTTON_VIEWALLMEMBERS:
                return TranslationAPIFacade::getInstance()->__('View all members', 'poptheme-wassup');
        }
        
        return parent::getTitle($component, $props);
    }

    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_UREAAL_BUTTON_EDITMEMBERSHIP:
                return POP_TARGET_ADDONS;
        }
        
        return parent::getLinktarget($component, $props);
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component->name) {
            case self::COMPONENT_UREAAL_BUTTON_EDITMEMBERSHIP:
            case self::COMPONENT_UREAAL_BUTTON_VIEWALLMEMBERS:
                $ret .= ' btn btn-xs btn-link';
                break;
        }

        return $ret;
    }
}


