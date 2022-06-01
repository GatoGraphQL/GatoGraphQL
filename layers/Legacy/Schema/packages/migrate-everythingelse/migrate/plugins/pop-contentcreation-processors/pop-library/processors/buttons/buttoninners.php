<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentCreation_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const COMPONENT_BUTTONINNER_POSTEDIT = 'buttoninner-postedit';
    public final const COMPONENT_BUTTONINNER_POSTVIEW = 'buttoninner-postview';
    public final const COMPONENT_BUTTONINNER_POSTPREVIEW = 'buttoninner-postpreview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTONINNER_POSTEDIT],
            [self::class, self::COMPONENT_BUTTONINNER_POSTVIEW],
            [self::class, self::COMPONENT_BUTTONINNER_POSTPREVIEW],
        );
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONINNER_POSTPREVIEW:
                return 'fa-fw fa-eye';
            
            case self::COMPONENT_BUTTONINNER_POSTEDIT:
                return 'fa-fw fa-edit';
            
            case self::COMPONENT_BUTTONINNER_POSTVIEW:
                return 'fa-fw fa-link';
        }

        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONINNER_POSTEDIT:
                return TranslationAPIFacade::getInstance()->__('Edit', 'pop-coreprocessors');
            
            case self::COMPONENT_BUTTONINNER_POSTVIEW:
                return TranslationAPIFacade::getInstance()->__('View', 'pop-coreprocessors');
            
            case self::COMPONENT_BUTTONINNER_POSTPREVIEW:
                return TranslationAPIFacade::getInstance()->__('Preview', 'pop-coreprocessors');
        }

        return parent::getBtnTitle($component);
    }
}


