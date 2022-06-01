<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const COMPONENT_UREAAL_BUTTONINNER_EDITMEMBERSHIP = 'ure-aal-buttoninner-editmembership';
    public final const COMPONENT_UREAAL_BUTTONINNER_VIEWALLMEMBERS = 'ure-aal-buttoninner-viewallmembers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_UREAAL_BUTTONINNER_EDITMEMBERSHIP],
            [self::class, self::COMPONENT_UREAAL_BUTTONINNER_VIEWALLMEMBERS],
        );
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_UREAAL_BUTTONINNER_EDITMEMBERSHIP:
                return 'fa-fw fa-asterisk';

            case self::COMPONENT_UREAAL_BUTTONINNER_VIEWALLMEMBERS:
                return 'fa-fw fa-users';
        }

        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_UREAAL_BUTTONINNER_EDITMEMBERSHIP:
                return TranslationAPIFacade::getInstance()->__('Edit membership', 'poptheme-wassup');

            case self::COMPONENT_UREAAL_BUTTONINNER_VIEWALLMEMBERS:
                return TranslationAPIFacade::getInstance()->__('View all members', 'poptheme-wassup');
        }

        return parent::getBtnTitle($component);
    }
}


