<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentCreation_Module_Processor_ViewComponentButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const COMPONENT_VIEWCOMPONENT_BUTTONINNER_FLAG_SOCIALMEDIA = 'viewcomponent-buttoninner-flag-socialmedia';
    public final const COMPONENT_VIEWCOMPONENT_BUTTONINNER_FLAG_PREVIEWDROPDOWN = 'viewcomponent-buttoninner-flag-previewdropdown';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_FLAG_SOCIALMEDIA,
            self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_FLAG_PREVIEWDROPDOWN,
        );
    }
    
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_FLAG_SOCIALMEDIA:
                return 'fa-fw fa-flag fa-lg';

            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_FLAG_PREVIEWDROPDOWN:
                return 'fa-fw fa-flag';
        }
        
        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_FLAG_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Flag as inappropriate', 'pop-coreprocessors');
        }
        
        return parent::getBtnTitle($component);
    }
}


