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

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_CAPTCHA:
                return TranslationAPIFacade::getInstance()->__('Captcha', 'pop-coreprocessors');
        }

        return parent::getLabelText($componentVariation, $props);
    }

    public function isMandatory(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_CAPTCHA:
                return true;
        }

        return parent::isMandatory($componentVariation, $props);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);
        $this->addJsmethod($ret, 'addDomainClass');
        return $ret;
    }

    public function getImmutableJsconfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($componentVariation, $props);

        // For function addDomainClass
        $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // If we don't use the loggedinuser-data, then show the inputs always
        if (!PoP_FormUtils::useLoggedinuserData()) {
            $this->appendProp($componentVariation, $props, 'wrapper-class', 'visible-always');
        }

        parent::initModelProps($componentVariation, $props);
    }
}



