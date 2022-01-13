<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPTheme_Wassup_Module_Processor_SubmitButtons extends PoP_Module_Processor_SubmitButtonsBase
{
    public const MODULE_SUBMITBUTTON_INSTANTANEOUSSEARCH = 'submitbutton-instantaneoussearch';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SUBMITBUTTON_INSTANTANEOUSSEARCH],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SUBMITBUTTON_INSTANTANEOUSSEARCH:
                return TranslationAPIFacade::getInstance()->__('Search', 'poptheme-wassup');
        }

        return parent::getLabel($module, $props);
    }

    public function getBtnClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SUBMITBUTTON_INSTANTANEOUSSEARCH:
                return 'btn btn-info';
        }

        return parent::getBtnClass($module, $props);
    }

    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SUBMITBUTTON_INSTANTANEOUSSEARCH:
                return 'fa fa-search';
        }

        return parent::getFontawesome($module, $props);
    }
}


