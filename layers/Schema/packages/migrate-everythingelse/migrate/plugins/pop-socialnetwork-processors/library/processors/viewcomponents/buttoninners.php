<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class PoP_SocialNetwork_Module_Processor_ViewComponentButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public const MODULE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_PREVIEW = 'viewcomponent-buttoninner-sendmessage-preview';
    public const MODULE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_FULL = 'viewcomponent-buttoninner-sidebar-sendmessage-full';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_PREVIEW],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_FULL],
        );
    }
    
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_PREVIEW:
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_FULL:
                return 'fa-fw fa-envelope-o';
        }
        
        return parent::getFontawesome($module, $props);
    }

    public function getBtnTitle(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_PREVIEW:
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_SENDMESSAGE_FULL:
                return TranslationAPIFacade::getInstance()->__('Send message', 'pop-coreprocessors');
        }
        
        return parent::getBtnTitle($module);
    }
}


