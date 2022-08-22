<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserLogin_Module_Processor_HTMLCodes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const COMPONENT_HTMLCODE_USERMUSTBELOGGEDIN = 'htmlcode-usermustbeloggedin';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_HTMLCODE_USERMUSTBELOGGEDIN,
        );
    }

    public function getHtmlTag(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_HTMLCODE_USERMUSTBELOGGEDIN:
                return 'p';
        }
    
        return parent::getHtmlTag($component, $props);
    }

    public function getCode(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_HTMLCODE_USERMUSTBELOGGEDIN:
                return TranslationAPIFacade::getInstance()->__('You need to be logged in to access this page.', 'pop-userlogin-processors');
        }

        return parent::getCode($component, $props);
    }
}



