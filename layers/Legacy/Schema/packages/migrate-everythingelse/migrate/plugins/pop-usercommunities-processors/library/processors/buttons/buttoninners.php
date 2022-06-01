<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const COMPONENT_URE_BUTTONINNER_EDITMEMBERSHIP = 'ure-buttoninner-editmembership';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_BUTTONINNER_EDITMEMBERSHIP],
        );
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_BUTTONINNER_EDITMEMBERSHIP:
                return 'fa-fw fa-asterisk';
        }

        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_BUTTONINNER_EDITMEMBERSHIP:
                return TranslationAPIFacade::getInstance()->__('Edit membership', 'ure-popprocessors');
        }

        return parent::getBtnTitle($component);
    }
}


