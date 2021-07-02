<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class Wassup_URE_AAL_Module_Processor_MemberStatusLayouts extends GD_URE_Module_Processor_MemberStatusLayoutsBase
{
    public const MODULE_UREAAL_LAYOUTUSER_MEMBERSTATUS = 'ure-aal-layoutuser-memberstatus-nodesc';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_UREAAL_LAYOUTUSER_MEMBERSTATUS],
        );
    }

    public function getDescription(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_UREAAL_LAYOUTUSER_MEMBERSTATUS:
                return sprintf(
                    '<em>%s</em>',
                    TranslationAPIFacade::getInstance()->__('Status:', 'poptheme-wassup')
                );
        }
    
        return parent::getDescription($module, $props);
    }
}


