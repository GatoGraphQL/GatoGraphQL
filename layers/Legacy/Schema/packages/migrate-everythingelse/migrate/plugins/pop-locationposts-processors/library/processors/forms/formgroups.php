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


    public function getLabelClass(array $componentVariation)
    {
        $ret = parent::getLabelClass($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $componentVariation)
    {
        $ret = parent::getFormcontrolClass($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUTGROUP_LOCATIONPOSTCATEGORIES:
                return [GD_Custom_EM_Module_Processor_MultiSelectFormInputs::class, GD_Custom_EM_Module_Processor_MultiSelectFormInputs::MODULE_FORMINPUT_LOCATIONPOSTCATEGORIES];

            case self::MODULE_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES:
                return [GD_Custom_EM_Module_Processor_MultiSelectFormInputs::class, GD_Custom_EM_Module_Processor_MultiSelectFormInputs::MODULE_FILTERINPUT_LOCATIONPOSTCATEGORIES];
        }

        return parent::getComponentSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUTGROUP_LOCATIONPOSTCATEGORIES:
                // case self::MODULE_FILTERINPUTGROUP_LOCATIONPOSTCATEGORIES:

                $component = $this->getComponentSubmodule($componentVariation);
                $this->setProp($component, $props, 'label', TranslationAPIFacade::getInstance()->__('Select categories', 'pop-locationposts-processors'));
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



