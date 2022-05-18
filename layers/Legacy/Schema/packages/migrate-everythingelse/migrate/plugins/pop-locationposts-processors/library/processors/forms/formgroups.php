<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_LOCATIONPOSTCATEGORIES = 'forminputgroup-locationpostcategories';
    public final const COMPONENT_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES = 'filterinputgroup-locationpostcategories';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUTGROUP_LOCATIONPOSTCATEGORIES],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES],
        );
    }


    public function getLabelClass(array $component)
    {
        $ret = parent::getLabelClass($component);

        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $component)
    {
        $ret = parent::getFormcontrolClass($component);

        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_LOCATIONPOSTCATEGORIES:
                return [GD_Custom_EM_Module_Processor_MultiSelectFormInputs::class, GD_Custom_EM_Module_Processor_MultiSelectFormInputs::COMPONENT_FORMINPUT_LOCATIONPOSTCATEGORIES];

            case self::COMPONENT_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES:
                return [GD_Custom_EM_Module_Processor_MultiSelectFormInputs::class, GD_Custom_EM_Module_Processor_MultiSelectFormInputs::COMPONENT_FILTERINPUT_LOCATIONPOSTCATEGORIES];
        }

        return parent::getComponentSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_LOCATIONPOSTCATEGORIES:
                // case self::COMPONENT_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES:

                $component = $this->getComponentSubmodule($component);
                $this->setProp($component, $props, 'label', TranslationAPIFacade::getInstance()->__('Select categories', 'pop-locationposts-processors'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



