<?php

class GD_URE_Module_Processor_CreateProfileForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_PROFILEORGANIZATION_CREATE = 'form-profileorganization-create';
    public final const MODULE_FORM_PROFILEINDIVIDUAL_CREATE = 'form-profileindividual-create';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORM_PROFILEORGANIZATION_CREATE],
            [self::class, self::MODULE_FORM_PROFILEINDIVIDUAL_CREATE],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORM_PROFILEORGANIZATION_CREATE:
                // Allow the Custom implementation to override
                return \PoP\Root\App::applyFilters(
                    'GD_URE_Module_Processor_CreateProfileForms:getInnerSubmodule:profileorganization',
                    [GD_URE_Module_Processor_CreateProfileOrganizationFormInners::class, GD_URE_Module_Processor_CreateProfileOrganizationFormInners::MODULE_FORMINNER_PROFILEORGANIZATION_CREATE]
                );

            case self::MODULE_FORM_PROFILEINDIVIDUAL_CREATE:
                return \PoP\Root\App::applyFilters(
                    'GD_URE_Module_Processor_CreateProfileForms:getInnerSubmodule:profileindividual',
                    [GD_URE_Module_Processor_CreateProfileIndividualFormInners::class, GD_URE_Module_Processor_CreateProfileIndividualFormInners::MODULE_FORMINNER_PROFILEINDIVIDUAL_CREATE]
                );
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_FORM_PROFILEORGANIZATION_CREATE:
            case self::MODULE_FORM_PROFILEINDIVIDUAL_CREATE:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_FORM_PROFILEORGANIZATION_CREATE:
            case self::MODULE_FORM_PROFILEINDIVIDUAL_CREATE:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORM_PROFILEORGANIZATION_CREATE:
            case self::MODULE_FORM_PROFILEINDIVIDUAL_CREATE:
                // Do not show if user already logged in
                $this->appendProp($componentVariation, $props, 'class', 'visible-notloggedin');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



