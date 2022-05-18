<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Captcha_Module_Processor_FormInputGroups extends PoP_Module_Processor_SubcomponentFormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_CAPTCHA = 'forminputgroup-captcha';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_CAPTCHA],
        );
    }

    public function getComponentSubname(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUTGROUP_CAPTCHA:
                return 'input';
        }

        return parent::getComponentSubname($componentVariation);
    }

    public function getComponentSubmodule(array $componentVariation)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_CAPTCHA => [PoP_Module_Processor_CaptchaFormInputs::class, PoP_Module_Processor_CaptchaFormInputs::MODULE_FORMINPUT_CAPTCHA],
        );

        if ($component = $components[$componentVariation[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($componentVariation);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUTGROUP_CAPTCHA:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUTGROUP_CAPTCHA:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUTGROUP_CAPTCHA:
                $this->appendProp($componentVariation, $props, 'class', 'visible-notloggedin');

                // If we don't use the loggedinuser-data, then show the inputs always
                if (!PoP_FormUtils::useLoggedinuserData()) {
                    $this->appendProp($componentVariation, $props, 'class', 'visible-always');
                }

                $placeholder = TranslationAPIFacade::getInstance()->__('Type captcha here...', 'pop-coreprocessors');
                $this->setProp($this->getComponentSubmodule($componentVariation), $props, 'placeholder', $placeholder);
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



