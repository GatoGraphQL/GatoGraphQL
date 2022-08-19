<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_ViewComponentButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const COMPONENT_VIEWCOMPONENT_BUTTONINNER_REPLYCOMMENT = 'viewcomponent-buttoninner-replycomment';
    public final const COMPONENT_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT = 'viewcomponent-buttoninner-addcomment';
    public final const COMPONENT_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT_FULL = 'viewcomponent-buttoninner-addcomment-full';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_REPLYCOMMENT,
            self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT,
            self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT_FULL,
        );
    }
    
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_REPLYCOMMENT:
                return 'fa-fw fa-reply';

            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT_FULL:
                return 'fa-fw fa-comments';
        }
        
        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_REPLYCOMMENT:
                return TranslationAPIFacade::getInstance()->__('Reply', 'pop-coreprocessors');

            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_ADDCOMMENT_FULL:
                return TranslationAPIFacade::getInstance()->__('Write a comment', 'pop-coreprocessors');
        }
        
        return parent::getBtnTitle($component);
    }
}


