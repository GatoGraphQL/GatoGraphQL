<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_ViewComponentButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public const MODULE_VIEWCOMPONENT_BUTTONINNER_REPLYCOMMENT = 'viewcomponent-buttoninner-replycomment';
    public const MODULE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT = 'viewcomponent-buttoninner-addcomment';
    public const MODULE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT_FULL = 'viewcomponent-buttoninner-addcomment-full';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONINNER_REPLYCOMMENT],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT_FULL],
        );
    }
    
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_REPLYCOMMENT:
                return 'fa-fw fa-reply';

            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT:
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT_FULL:
                return 'fa-fw fa-comments';
        }
        
        return parent::getFontawesome($module, $props);
    }

    public function getBtnTitle(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_REPLYCOMMENT:
                return TranslationAPIFacade::getInstance()->__('Reply', 'pop-coreprocessors');

            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT:
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT_FULL:
                return TranslationAPIFacade::getInstance()->__('Write a comment', 'pop-coreprocessors');
        }
        
        return parent::getBtnTitle($module);
    }
}


