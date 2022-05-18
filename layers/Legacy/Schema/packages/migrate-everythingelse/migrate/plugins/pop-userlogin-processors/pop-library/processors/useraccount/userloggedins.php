<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_UserLoggedIns extends PoP_Module_Processor_UserLoggedInsBase
{
    public final const MODULE_USERACCOUNT_USERLOGGEDINWELCOME = 'useraccount-userloggedinwelcome';
    public final const MODULE_USERACCOUNT_USERLOGGEDINPROMPT = 'useraccount-userloggedinprompt';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_USERACCOUNT_USERLOGGEDINWELCOME],
            [self::class, self::MODULE_USERACCOUNT_USERLOGGEDINPROMPT],
        );
    }

    public function getTitleTop(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_USERACCOUNT_USERLOGGEDINWELCOME:
                return TranslationAPIFacade::getInstance()->__('Welcome', 'pop-coreprocessors');

            case self::MODULE_USERACCOUNT_USERLOGGEDINPROMPT:
                return TranslationAPIFacade::getInstance()->__('You are already logged in as', 'pop-coreprocessors');
        }

        return parent::getTitleTop($componentVariation, $props);
    }

    public function getTitleBottom(array $componentVariation, array &$props)
    {
        $cmsuseraccountapi = \PoP\UserAccount\FunctionAPIFactory::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_USERACCOUNT_USERLOGGEDINPROMPT:
                return sprintf(
                    '<p><a href="%s">%s</a></p>',
                    $cmsuseraccountapi->getLogoutURL(),
                    TranslationAPIFacade::getInstance()->__('Logout?', 'pop-coreprocessors')
                );
        }

        return parent::getTitleBottom($componentVariation, $props);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_USERACCOUNT_USERLOGGEDINWELCOME:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_USERACCOUNT_USERLOGGEDINWELCOME:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-loggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_USERACCOUNT_USERLOGGEDINWELCOME:
                $this->appendProp($componentVariation, $props, 'class', 'visible-loggedin');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


