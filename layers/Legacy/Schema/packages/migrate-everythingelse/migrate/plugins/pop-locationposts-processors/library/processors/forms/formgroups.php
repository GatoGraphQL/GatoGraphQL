<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_LOCATIONPOSTCATEGORIES = 'forminputgroup-locationpostcategories';
    public final const MODULE_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES = 'filterinputgroup-locationpostcategories';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_LOCATIONPOSTCATEGORIES],
            [self::class, self::MODULE_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES],
        );
    }


    public function getLabelClass(array $module)
    {
        $ret = parent::getLabelClass($module);

        switch ($module[1]) {
            case self::MODULE_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $module)
    {
        $ret = parent::getFormcontrolClass($module);

        switch ($module[1]) {
            case self::MODULE_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_LOCATIONPOSTCATEGORIES:
                return [GD_Custom_EM_Module_Processor_MultiSelectFormInputs::class, GD_Custom_EM_Module_Processor_MultiSelectFormInputs::MODULE_FORMINPUT_LOCATIONPOSTCATEGORIES];

            case self::MODULE_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES:
                return [GD_Custom_EM_Module_Processor_MultiSelectFormInputs::class, GD_Custom_EM_Module_Processor_MultiSelectFormInputs::MODULE_FILTERINPUT_LOCATIONPOSTCATEGORIES];
        }

        return parent::getComponentSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_LOCATIONPOSTCATEGORIES:
                // case self::MODULE_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES:

                $component = $this->getComponentSubmodule($module);
                $this->setProp($component, $props, 'label', TranslationAPIFacade::getInstance()->__('Select categories', 'pop-locationposts-processors'));
                break;
        }

        parent::initModelProps($module, $props);
    }
}



