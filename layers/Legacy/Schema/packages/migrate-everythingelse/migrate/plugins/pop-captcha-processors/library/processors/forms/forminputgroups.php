<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Captcha_Module_Processor_FormInputGroups extends PoP_Module_Processor_SubcomponentFormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_CAPTCHA = 'forminputgroup-captcha';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_CAPTCHA],
        );
    }

    public function getComponentSubname(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_CAPTCHA:
                return 'input';
        }

        return parent::getComponentSubname($module);
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_CAPTCHA => [PoP_Module_Processor_CaptchaFormInputs::class, PoP_Module_Processor_CaptchaFormInputs::MODULE_FORMINPUT_CAPTCHA],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_CAPTCHA:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_CAPTCHA:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_CAPTCHA:
                $this->appendProp($module, $props, 'class', 'visible-notloggedin');

                // If we don't use the loggedinuser-data, then show the inputs always
                if (!PoP_FormUtils::useLoggedinuserData()) {
                    $this->appendProp($module, $props, 'class', 'visible-always');
                }

                $placeholder = TranslationAPIFacade::getInstance()->__('Type captcha here...', 'pop-coreprocessors');
                $this->setProp($this->getComponentSubmodule($module), $props, 'placeholder', $placeholder);
                break;
        }

        parent::initModelProps($module, $props);
    }
}



