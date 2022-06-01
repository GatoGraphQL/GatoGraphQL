<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPTheme_Wassup_Module_Processor_SubmitButtons extends PoP_Module_Processor_SubmitButtonsBase
{
    public final const COMPONENT_SUBMITBUTTON_INSTANTANEOUSSEARCH = 'submitbutton-instantaneoussearch';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SUBMITBUTTON_INSTANTANEOUSSEARCH],
        );
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_SUBMITBUTTON_INSTANTANEOUSSEARCH:
                return TranslationAPIFacade::getInstance()->__('Search', 'poptheme-wassup');
        }

        return parent::getLabel($component, $props);
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_SUBMITBUTTON_INSTANTANEOUSSEARCH:
                return 'btn btn-info';
        }

        return parent::getBtnClass($component, $props);
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_SUBMITBUTTON_INSTANTANEOUSSEARCH:
                return 'fa fa-search';
        }

        return parent::getFontawesome($component, $props);
    }
}


