<?php

class PoP_Forms_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_NAME = 'gf-forminputgroup-field-name';
    public final const MODULE_FORMINPUTGROUP_EMAIL = 'gf-forminputgroup-field-email';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_NAME],
            [self::class, self::MODULE_FORMINPUTGROUP_EMAIL],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_NAME => [PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME],
            self::MODULE_FORMINPUTGROUP_EMAIL => [PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL],
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
            case self::MODULE_FORMINPUTGROUP_NAME:
            case self::MODULE_FORMINPUTGROUP_EMAIL:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_NAME:
            case self::MODULE_FORMINPUTGROUP_EMAIL:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_NAME:
            case self::MODULE_FORMINPUTGROUP_EMAIL:
                $this->appendProp($module, $props, 'class', 'visible-notloggedin');

                // If we don't use the loggedinuser-data, then show the inputs always
                if (!PoP_FormUtils::useLoggedinuserData()) {
                    $this->appendProp($module, $props, 'class', 'visible-always');
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}



