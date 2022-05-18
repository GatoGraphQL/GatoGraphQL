<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class Wassup_URE_AAL_Module_Processor_MemberPrivilegesLayouts extends GD_URE_Module_Processor_MemberPrivilegesLayoutsBase
{
    public final const MODULE_UREAAL_LAYOUTUSER_MEMBERPRIVILEGES = 'ure-aal-layoutuser-memberprivileges-desc';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_UREAAL_LAYOUTUSER_MEMBERPRIVILEGES],
        );
    }

    public function getDescription(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_UREAAL_LAYOUTUSER_MEMBERPRIVILEGES:
                return sprintf(
                    '<em>%s</em>',
                    TranslationAPIFacade::getInstance()->__('Privileges:', 'poptheme-wassup')
                );
        }

        return parent::getDescription($module, $props);
    }
}


