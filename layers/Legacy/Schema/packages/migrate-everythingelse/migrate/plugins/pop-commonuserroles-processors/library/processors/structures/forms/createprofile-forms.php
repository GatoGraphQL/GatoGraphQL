<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class GD_URE_Module_Processor_CreateProfileForms extends PoP_Module_Processor_FormsBase
{
    public const MODULE_FORM_PROFILEORGANIZATION_CREATE = 'form-profileorganization-create';
    public const MODULE_FORM_PROFILEINDIVIDUAL_CREATE = 'form-profileindividual-create';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORM_PROFILEORGANIZATION_CREATE],
            [self::class, self::MODULE_FORM_PROFILEINDIVIDUAL_CREATE],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORM_PROFILEORGANIZATION_CREATE:
                // Allow the Custom implementation to override
                return HooksAPIFacade::getInstance()->applyFilters(
                    'GD_URE_Module_Processor_CreateProfileForms:getInnerSubmodule:profileorganization',
                    [GD_URE_Module_Processor_CreateProfileOrganizationFormInners::class, GD_URE_Module_Processor_CreateProfileOrganizationFormInners::MODULE_FORMINNER_PROFILEORGANIZATION_CREATE]
                );

            case self::MODULE_FORM_PROFILEINDIVIDUAL_CREATE:
                return HooksAPIFacade::getInstance()->applyFilters(
                    'GD_URE_Module_Processor_CreateProfileForms:getInnerSubmodule:profileindividual',
                    [GD_URE_Module_Processor_CreateProfileIndividualFormInners::class, GD_URE_Module_Processor_CreateProfileIndividualFormInners::MODULE_FORMINNER_PROFILEINDIVIDUAL_CREATE]
                );
        }

        return parent::getInnerSubmodule($module);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_FORM_PROFILEORGANIZATION_CREATE:
            case self::MODULE_FORM_PROFILEINDIVIDUAL_CREATE:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_FORM_PROFILEORGANIZATION_CREATE:
            case self::MODULE_FORM_PROFILEINDIVIDUAL_CREATE:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORM_PROFILEORGANIZATION_CREATE:
            case self::MODULE_FORM_PROFILEINDIVIDUAL_CREATE:
                // Do not show if user already logged in
                $this->appendProp($module, $props, 'class', 'visible-notloggedin');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



