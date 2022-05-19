<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_UserLoggedIns extends PoP_Module_Processor_UserLoggedInsBase
{
    public final const COMPONENT_USERACCOUNT_USERLOGGEDINWELCOME = 'useraccount-userloggedinwelcome';
    public final const COMPONENT_USERACCOUNT_USERLOGGEDINPROMPT = 'useraccount-userloggedinprompt';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_USERACCOUNT_USERLOGGEDINWELCOME],
            [self::class, self::COMPONENT_USERACCOUNT_USERLOGGEDINPROMPT],
        );
    }

    public function getTitleTop(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_USERACCOUNT_USERLOGGEDINWELCOME:
                return TranslationAPIFacade::getInstance()->__('Welcome', 'pop-coreprocessors');

            case self::COMPONENT_USERACCOUNT_USERLOGGEDINPROMPT:
                return TranslationAPIFacade::getInstance()->__('You are already logged in as', 'pop-coreprocessors');
        }

        return parent::getTitleTop($component, $props);
    }

    public function getTitleBottom(array $component, array &$props)
    {
        $cmsuseraccountapi = \PoP\UserAccount\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_USERACCOUNT_USERLOGGEDINPROMPT:
                return sprintf(
                    '<p><a href="%s">%s</a></p>',
                    $cmsuseraccountapi->getLogoutURL(),
                    TranslationAPIFacade::getInstance()->__('Logout?', 'pop-coreprocessors')
                );
        }

        return parent::getTitleBottom($component, $props);
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_USERACCOUNT_USERLOGGEDINWELCOME:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_USERACCOUNT_USERLOGGEDINWELCOME:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-loggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_USERACCOUNT_USERLOGGEDINWELCOME:
                $this->appendProp($component, $props, 'class', 'visible-loggedin');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


