<?php

class PoP_Forms_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_NAME = 'gf-forminputgroup-field-name';
    public final const MODULE_FORMINPUTGROUP_EMAIL = 'gf-forminputgroup-field-email';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUTGROUP_NAME],
            [self::class, self::COMPONENT_FORMINPUTGROUP_EMAIL],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_NAME => [PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NAME],
            self::COMPONENT_FORMINPUTGROUP_EMAIL => [PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_EMAIL],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_NAME:
            case self::COMPONENT_FORMINPUTGROUP_EMAIL:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_NAME:
            case self::COMPONENT_FORMINPUTGROUP_EMAIL:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_NAME:
            case self::COMPONENT_FORMINPUTGROUP_EMAIL:
                $this->appendProp($component, $props, 'class', 'visible-notloggedin');

                // If we don't use the loggedinuser-data, then show the inputs always
                if (!PoP_FormUtils::useLoggedinuserData()) {
                    $this->appendProp($component, $props, 'class', 'visible-always');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}



