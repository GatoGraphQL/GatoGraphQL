<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserLogin_Module_Processor_HTMLCodes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const MODULE_HTMLCODE_USERMUSTBELOGGEDIN = 'htmlcode-usermustbeloggedin';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_HTMLCODE_USERMUSTBELOGGEDIN],
        );
    }

    public function getHtmlTag(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_HTMLCODE_USERMUSTBELOGGEDIN:
                return 'p';
        }
    
        return parent::getHtmlTag($componentVariation, $props);
    }

    public function getCode(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_HTMLCODE_USERMUSTBELOGGEDIN:
                return TranslationAPIFacade::getInstance()->__('You need to be logged in to access this page.', 'pop-userlogin-processors');
        }

        return parent::getCode($componentVariation, $props);
    }
}



