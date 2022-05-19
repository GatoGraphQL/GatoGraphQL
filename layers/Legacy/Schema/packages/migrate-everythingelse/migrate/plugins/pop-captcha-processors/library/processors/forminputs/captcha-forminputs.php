<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CaptchaFormInputs extends PoP_Module_Processor_CaptchaFormInputsBase
{
    public final const COMPONENT_FORMINPUT_CAPTCHA = 'forminput-captcha';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_CAPTCHA],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_CAPTCHA:
                return TranslationAPIFacade::getInstance()->__('Captcha', 'pop-coreprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    public function isMandatory(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_CAPTCHA:
                return true;
        }

        return parent::isMandatory($component, $props);
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);
        $this->addJsmethod($ret, 'addDomainClass');
        return $ret;
    }

    public function getImmutableJsconfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        // For function addDomainClass
        $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {

        // If we don't use the loggedinuser-data, then show the inputs always
        if (!PoP_FormUtils::useLoggedinuserData()) {
            $this->appendProp($component, $props, 'wrapper-class', 'visible-always');
        }

        parent::initModelProps($component, $props);
    }
}



