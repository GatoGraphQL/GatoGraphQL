<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_UserLoggedIns extends PoP_Module_Processor_UserLoggedInsBase
{
    public const MODULE_USERACCOUNT_USERLOGGEDINWELCOME = 'useraccount-userloggedinwelcome';
    public const MODULE_USERACCOUNT_USERLOGGEDINPROMPT = 'useraccount-userloggedinprompt';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_USERACCOUNT_USERLOGGEDINWELCOME],
            [self::class, self::MODULE_USERACCOUNT_USERLOGGEDINPROMPT],
        );
    }

    public function getTitleTop(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_USERACCOUNT_USERLOGGEDINWELCOME:
                return TranslationAPIFacade::getInstance()->__('Welcome', 'pop-coreprocessors');

            case self::MODULE_USERACCOUNT_USERLOGGEDINPROMPT:
                return TranslationAPIFacade::getInstance()->__('You are already logged in as', 'pop-coreprocessors');
        }

        return parent::getTitleTop($module, $props);
    }

    public function getTitleBottom(array $module, array &$props)
    {
        $cmsuseraccountapi = \PoP\UserAccount\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_USERACCOUNT_USERLOGGEDINPROMPT:
                return sprintf(
                    '<p><a href="%s">%s</a></p>',
                    $cmsuseraccountapi->getLogoutURL(),
                    TranslationAPIFacade::getInstance()->__('Logout?', 'pop-coreprocessors')
                );
        }

        return parent::getTitleBottom($module, $props);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_USERACCOUNT_USERLOGGEDINWELCOME:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_USERACCOUNT_USERLOGGEDINWELCOME:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-loggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_USERACCOUNT_USERLOGGEDINWELCOME:
                $this->appendProp($module, $props, 'class', 'visible-loggedin');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


