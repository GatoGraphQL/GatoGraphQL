<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CaptchaFormInputs extends PoP_Module_Processor_CaptchaFormInputsBase
{
    public final const MODULE_FORMINPUT_CAPTCHA = 'forminput-captcha';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_CAPTCHA],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CAPTCHA:
                return TranslationAPIFacade::getInstance()->__('Captcha', 'pop-coreprocessors');
        }

        return parent::getLabelText($module, $props);
    }

    public function isMandatory(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CAPTCHA:
                return true;
        }

        return parent::isMandatory($module, $props);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);
        $this->addJsmethod($ret, 'addDomainClass');
        return $ret;
    }

    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        // For function addDomainClass
        $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // If we don't use the loggedinuser-data, then show the inputs always
        if (!PoP_FormUtils::useLoggedinuserData()) {
            $this->appendProp($module, $props, 'wrapper-class', 'visible-always');
        }

        parent::initModelProps($module, $props);
    }
}



