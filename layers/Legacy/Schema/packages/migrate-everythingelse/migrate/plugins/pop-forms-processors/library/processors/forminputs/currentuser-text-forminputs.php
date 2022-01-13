<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

class PoP_Forms_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public const MODULE_FORMINPUT_NAME = 'gf-field-name';
    public const MODULE_FORMINPUT_EMAIL = 'gf-field-email';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_NAME],
            [self::class, self::MODULE_FORMINPUT_EMAIL],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_NAME:
                return TranslationAPIFacade::getInstance()->__('Your Name', 'pop-genericforms');

            case self::MODULE_FORMINPUT_EMAIL:
                return TranslationAPIFacade::getInstance()->__('Your Email', 'pop-genericforms');
        }

        return parent::getLabelText($module, $props);
    }

    public function isMandatory(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_NAME:
            case self::MODULE_FORMINPUT_EMAIL:
                return true;
        }

        return parent::isMandatory($module, $props);
    }

    public function getInputOptions(array $module): array
    {
        $options = parent::getInputOptions($module);

        // When submitting the form, if user is logged in, then use these values.
        // Otherwise, use the values sent in the form
        if (PoP_FormUtils::useLoggedinuserData() && doingPost() && \PoP\Root\App::getState('is-user-logged-in')) {
            $user_id = \PoP\Root\App::getState('current-user-id');
            $userTypeAPI = UserTypeAPIFacade::getInstance();
            switch ($module[1]) {
                case self::MODULE_FORMINPUT_NAME:
                    $options['selected'] = $userTypeAPI->getUserDisplayName($user_id);
                    break;

                case self::MODULE_FORMINPUT_EMAIL:
                    $options['selected'] = $userTypeAPI->getUserEmail($user_id);
                    break;
            }
        }

        return $options;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_FORMINPUT_NAME:
            case self::MODULE_FORMINPUT_EMAIL:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_FORMINPUT_NAME:
            case self::MODULE_FORMINPUT_EMAIL:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_NAME:
            case self::MODULE_FORMINPUT_EMAIL:
                $this->appendProp($module, $props, 'class', 'visible-notloggedin');

                // If we don't use the loggedinuser-data, then show the inputs always
                if (!PoP_FormUtils::useLoggedinuserData()) {
                    $this->appendProp($module, $props, 'class', 'visible-always');
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}



