<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_URE_BUTTONINNER_EDITMEMBERSHIP = 'ure-buttoninner-editmembership';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_BUTTONINNER_EDITMEMBERSHIP],
        );
    }

    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_URE_BUTTONINNER_EDITMEMBERSHIP:
                return 'fa-fw fa-asterisk';
        }

        return parent::getFontawesome($module, $props);
    }

    public function getBtnTitle(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_BUTTONINNER_EDITMEMBERSHIP:
                return TranslationAPIFacade::getInstance()->__('Edit membership', 'ure-popprocessors');
        }

        return parent::getBtnTitle($module);
    }
}


