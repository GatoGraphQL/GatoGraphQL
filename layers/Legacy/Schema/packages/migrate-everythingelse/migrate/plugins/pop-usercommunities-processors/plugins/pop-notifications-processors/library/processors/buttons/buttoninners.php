<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class Custom_URE_AAL_PoPProcessors_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public const MODULE_UREAAL_BUTTONINNER_EDITMEMBERSHIP = 'ure-aal-buttoninner-editmembership';
    public const MODULE_UREAAL_BUTTONINNER_VIEWALLMEMBERS = 'ure-aal-buttoninner-viewallmembers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_UREAAL_BUTTONINNER_EDITMEMBERSHIP],
            [self::class, self::MODULE_UREAAL_BUTTONINNER_VIEWALLMEMBERS],
        );
    }

    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_UREAAL_BUTTONINNER_EDITMEMBERSHIP:
                return 'fa-fw fa-asterisk';

            case self::MODULE_UREAAL_BUTTONINNER_VIEWALLMEMBERS:
                return 'fa-fw fa-users';
        }

        return parent::getFontawesome($module, $props);
    }

    public function getBtnTitle(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_UREAAL_BUTTONINNER_EDITMEMBERSHIP:
                return TranslationAPIFacade::getInstance()->__('Edit membership', 'poptheme-wassup');

            case self::MODULE_UREAAL_BUTTONINNER_VIEWALLMEMBERS:
                return TranslationAPIFacade::getInstance()->__('View all members', 'poptheme-wassup');
        }

        return parent::getBtnTitle($module);
    }
}


