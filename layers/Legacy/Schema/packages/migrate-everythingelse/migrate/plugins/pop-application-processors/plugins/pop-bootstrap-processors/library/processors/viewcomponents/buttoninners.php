<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_GenericForms_Bootstrap_Module_Processor_ViewComponentButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const COMPONENT_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_SOCIALMEDIA = 'viewcomponent-buttoninner-sharebyemail-socialmedia';
    public final const COMPONENT_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN = 'viewcomponent-buttoninner-sharebyemail-previewdropdown';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_SOCIALMEDIA,
            self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN,
        );
    }
    
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_SOCIALMEDIA:
                return 'fa-fw fa-envelope fa-lg';
                    
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN:
                return 'fa-fw fa-envelope';
        }
        
        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Share by email', 'pop-coreprocessors');
        }
        
        return parent::getBtnTitle($component);
    }
}


