<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_URE_BUTTONINNER_EDITMEMBERSHIP = 'ure-buttoninner-editmembership';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_BUTTONINNER_EDITMEMBERSHIP],
        );
    }

    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_BUTTONINNER_EDITMEMBERSHIP:
                return 'fa-fw fa-asterisk';
        }

        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_BUTTONINNER_EDITMEMBERSHIP:
                return TranslationAPIFacade::getInstance()->__('Edit membership', 'ure-popprocessors');
        }

        return parent::getBtnTitle($component);
    }
}


