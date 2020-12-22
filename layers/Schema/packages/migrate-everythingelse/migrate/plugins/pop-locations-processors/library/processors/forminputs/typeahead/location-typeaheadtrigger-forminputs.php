<?php

class PoP_Module_Processor_LocationSelectableTypeaheadAlertFormComponents extends PoP_Module_Processor_LocationSelectableTypeaheadAlertFormComponentsBase
{
    public const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_LOCATIONS = 'formcomponent-selectabletypeaheadalert-locations';
    public const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_LOCATION = 'formcomponent-selectabletypeaheadalert-location';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_LOCATIONS],
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_LOCATION],
        );
    }
    
    public function getHiddeninputModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_LOCATIONS:
                return [GD_Processor_SelectableLocationHiddenInputFormInputs::class, GD_Processor_SelectableLocationHiddenInputFormInputs::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTLOCATIONS];

            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_LOCATION:
                return [GD_Processor_SelectableLocationHiddenInputFormInputs::class, GD_Processor_SelectableLocationHiddenInputFormInputs::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTLOCATION];
        }

        return parent::getHiddeninputModule($module);
    }

    public function isMultiple(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_LOCATIONS:
                return true;
        }

        return parent::isMultiple($module);
    }
}



