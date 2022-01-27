<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserLogin_Module_Processor_HTMLCodes extends PoP_Module_Processor_HTMLCodesBase
{
    public const MODULE_HTMLCODE_USERMUSTBELOGGEDIN = 'htmlcode-usermustbeloggedin';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_HTMLCODE_USERMUSTBELOGGEDIN],
        );
    }

    public function getHtmlTag(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_HTMLCODE_USERMUSTBELOGGEDIN:
                return 'p';
        }
    
        return parent::getHtmlTag($module, $props);
    }

    public function getCode(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_HTMLCODE_USERMUSTBELOGGEDIN:
                return TranslationAPIFacade::getInstance()->__('You need to be logged in to access this page.', 'pop-userlogin-processors');
        }

        return parent::getCode($module, $props);
    }
}



