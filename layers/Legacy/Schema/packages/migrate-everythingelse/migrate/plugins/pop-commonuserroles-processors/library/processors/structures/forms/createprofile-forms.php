<?php

class GD_URE_Module_Processor_CreateProfileForms extends PoP_Module_Processor_FormsBase
{
    public final const COMPONENT_FORM_PROFILEORGANIZATION_CREATE = 'form-profileorganization-create';
    public final const COMPONENT_FORM_PROFILEINDIVIDUAL_CREATE = 'form-profileindividual-create';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORM_PROFILEORGANIZATION_CREATE],
            [self::class, self::COMPONENT_FORM_PROFILEINDIVIDUAL_CREATE],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORM_PROFILEORGANIZATION_CREATE:
                // Allow the Custom implementation to override
                return \PoP\Root\App::applyFilters(
                    'GD_URE_Module_Processor_CreateProfileForms:getInnerSubmodule:profileorganization',
                    [GD_URE_Module_Processor_CreateProfileOrganizationFormInners::class, GD_URE_Module_Processor_CreateProfileOrganizationFormInners::COMPONENT_FORMINNER_PROFILEORGANIZATION_CREATE]
                );

            case self::COMPONENT_FORM_PROFILEINDIVIDUAL_CREATE:
                return \PoP\Root\App::applyFilters(
                    'GD_URE_Module_Processor_CreateProfileForms:getInnerSubmodule:profileindividual',
                    [GD_URE_Module_Processor_CreateProfileIndividualFormInners::class, GD_URE_Module_Processor_CreateProfileIndividualFormInners::COMPONENT_FORMINNER_PROFILEINDIVIDUAL_CREATE]
                );
        }

        return parent::getInnerSubmodule($component);
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_FORM_PROFILEORGANIZATION_CREATE:
            case self::COMPONENT_FORM_PROFILEINDIVIDUAL_CREATE:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_FORM_PROFILEORGANIZATION_CREATE:
            case self::COMPONENT_FORM_PROFILEINDIVIDUAL_CREATE:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_FORM_PROFILEORGANIZATION_CREATE:
            case self::COMPONENT_FORM_PROFILEINDIVIDUAL_CREATE:
                // Do not show if user already logged in
                $this->appendProp($component, $props, 'class', 'visible-notloggedin');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



