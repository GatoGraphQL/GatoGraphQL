<?php

class GD_URE_Module_Processor_CreateProfileForms extends PoP_Module_Processor_FormsBase
{
    public final const COMPONENT_FORM_PROFILEORGANIZATION_CREATE = 'form-profileorganization-create';
    public final const COMPONENT_FORM_PROFILEINDIVIDUAL_CREATE = 'form-profileindividual-create';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORM_PROFILEORGANIZATION_CREATE,
            self::COMPONENT_FORM_PROFILEINDIVIDUAL_CREATE,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORM_PROFILEORGANIZATION_CREATE:
                // Allow the Custom implementation to override
                return \PoP\Root\App::applyFilters(
                    'GD_URE_Module_Processor_CreateProfileForms:getInnerSubcomponent:profileorganization',
                    [GD_URE_Module_Processor_CreateProfileOrganizationFormInners::class, GD_URE_Module_Processor_CreateProfileOrganizationFormInners::COMPONENT_FORMINNER_PROFILEORGANIZATION_CREATE]
                );

            case self::COMPONENT_FORM_PROFILEINDIVIDUAL_CREATE:
                return \PoP\Root\App::applyFilters(
                    'GD_URE_Module_Processor_CreateProfileForms:getInnerSubcomponent:profileindividual',
                    [GD_URE_Module_Processor_CreateProfileIndividualFormInners::class, GD_URE_Module_Processor_CreateProfileIndividualFormInners::COMPONENT_FORMINNER_PROFILEINDIVIDUAL_CREATE]
                );
        }

        return parent::getInnerSubcomponent($component);
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_FORM_PROFILEORGANIZATION_CREATE:
            case self::COMPONENT_FORM_PROFILEINDIVIDUAL_CREATE:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        switch ($component->name) {
            case self::COMPONENT_FORM_PROFILEORGANIZATION_CREATE:
            case self::COMPONENT_FORM_PROFILEINDIVIDUAL_CREATE:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_FORM_PROFILEORGANIZATION_CREATE:
            case self::COMPONENT_FORM_PROFILEINDIVIDUAL_CREATE:
                // Do not show if user already logged in
                $this->appendProp($component, $props, 'class', 'visible-notloggedin');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



