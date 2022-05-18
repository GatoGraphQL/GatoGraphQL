<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPTheme_Wassup_Module_Processor_SubmitButtons extends PoP_Module_Processor_SubmitButtonsBase
{
    public final const MODULE_SUBMITBUTTON_INSTANTANEOUSSEARCH = 'submitbutton-instantaneoussearch';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SUBMITBUTTON_INSTANTANEOUSSEARCH],
        );
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBMITBUTTON_INSTANTANEOUSSEARCH:
                return TranslationAPIFacade::getInstance()->__('Search', 'poptheme-wassup');
        }

        return parent::getLabel($componentVariation, $props);
    }

    public function getBtnClass(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBMITBUTTON_INSTANTANEOUSSEARCH:
                return 'btn btn-info';
        }

        return parent::getBtnClass($componentVariation, $props);
    }

    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBMITBUTTON_INSTANTANEOUSSEARCH:
                return 'fa fa-search';
        }

        return parent::getFontawesome($componentVariation, $props);
    }
}


