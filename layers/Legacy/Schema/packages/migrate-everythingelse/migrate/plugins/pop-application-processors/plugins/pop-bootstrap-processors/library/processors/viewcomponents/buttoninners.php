<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_GenericForms_Bootstrap_Module_Processor_ViewComponentButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_SOCIALMEDIA = 'viewcomponent-buttoninner-sharebyemail-socialmedia';
    public final const MODULE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN = 'viewcomponent-buttoninner-sharebyemail-previewdropdown';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_SOCIALMEDIA],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN],
        );
    }
    
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_SOCIALMEDIA:
                return 'fa-fw fa-envelope fa-lg';
                    
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN:
                return 'fa-fw fa-envelope';
        }
        
        return parent::getFontawesome($module, $props);
    }

    public function getBtnTitle(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Share by email', 'pop-coreprocessors');
        }
        
        return parent::getBtnTitle($module);
    }
}


