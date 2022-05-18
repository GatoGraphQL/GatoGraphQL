<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_GenericForms_Bootstrap_Module_Processor_ViewComponentButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const COMPONENT_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_SOCIALMEDIA = 'viewcomponent-buttoninner-sharebyemail-socialmedia';
    public final const COMPONENT_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN = 'viewcomponent-buttoninner-sharebyemail-previewdropdown';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_SOCIALMEDIA],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN],
        );
    }
    
    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_SOCIALMEDIA:
                return 'fa-fw fa-envelope fa-lg';
                    
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN:
                return 'fa-fw fa-envelope';
        }
        
        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Share by email', 'pop-coreprocessors');
        }
        
        return parent::getBtnTitle($component);
    }
}


