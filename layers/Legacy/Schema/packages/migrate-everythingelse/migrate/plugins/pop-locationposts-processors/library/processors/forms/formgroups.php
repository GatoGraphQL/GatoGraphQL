<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_LOCATIONPOSTCATEGORIES = 'forminputgroup-locationpostcategories';
    public final const COMPONENT_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES = 'filterinputgroup-locationpostcategories';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUTGROUP_LOCATIONPOSTCATEGORIES,
            self::COMPONENT_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES,
        );
    }


    public function getLabelClass(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLabelClass($component);

        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getFormcontrolClass($component);

        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUTGROUP_LOCATIONPOSTCATEGORIES:
                return [GD_Custom_EM_Module_Processor_MultiSelectFormInputs::class, GD_Custom_EM_Module_Processor_MultiSelectFormInputs::COMPONENT_FORMINPUT_LOCATIONPOSTCATEGORIES];

            case self::COMPONENT_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES:
                return [GD_Custom_EM_Module_Processor_MultiSelectFormInputs::class, GD_Custom_EM_Module_Processor_MultiSelectFormInputs::COMPONENT_FILTERINPUT_LOCATIONPOSTCATEGORIES];
        }

        return parent::getComponentSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUTGROUP_LOCATIONPOSTCATEGORIES:
                // case self::COMPONENT_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES:

                $component = $this->getComponentSubcomponent($component);
                $this->setProp($component, $props, 'label', TranslationAPIFacade::getInstance()->__('Select categories', 'pop-locationposts-processors'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



