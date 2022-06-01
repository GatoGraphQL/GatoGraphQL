<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Captcha_Module_Processor_FormInputGroups extends PoP_Module_Processor_SubcomponentFormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_CAPTCHA = 'forminputgroup-captcha';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUTGROUP_CAPTCHA],
        );
    }

    public function getComponentSubname(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUTGROUP_CAPTCHA:
                return 'input';
        }

        return parent::getComponentSubname($component);
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_CAPTCHA => [PoP_Module_Processor_CaptchaFormInputs::class, PoP_Module_Processor_CaptchaFormInputs::COMPONENT_FORMINPUT_CAPTCHA],
        );

        if ($component = $components[$component->name] ?? null) {
            return $component;
        }

        return parent::getComponentSubcomponent($component);
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_FORMINPUTGROUP_CAPTCHA:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        switch ($component->name) {
            case self::COMPONENT_FORMINPUTGROUP_CAPTCHA:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUTGROUP_CAPTCHA:
                $this->appendProp($component, $props, 'class', 'visible-notloggedin');

                // If we don't use the loggedinuser-data, then show the inputs always
                if (!PoP_FormUtils::useLoggedinuserData()) {
                    $this->appendProp($component, $props, 'class', 'visible-always');
                }

                $placeholder = TranslationAPIFacade::getInstance()->__('Type captcha here...', 'pop-coreprocessors');
                $this->setProp($this->getComponentSubcomponent($component), $props, 'placeholder', $placeholder);
                break;
        }

        parent::initModelProps($component, $props);
    }
}



