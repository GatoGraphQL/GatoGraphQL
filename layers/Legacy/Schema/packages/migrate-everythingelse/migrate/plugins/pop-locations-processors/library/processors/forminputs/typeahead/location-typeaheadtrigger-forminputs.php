<?php

class PoP_Module_Processor_LocationSelectableTypeaheadAlertFormComponents extends PoP_Module_Processor_LocationSelectableTypeaheadAlertFormComponentsBase
{
    public final const COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_LOCATIONS = 'formcomponent-selectabletypeaheadalert-locations';
    public final const COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_LOCATION = 'formcomponent-selectabletypeaheadalert-location';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_LOCATIONS],
            [self::class, self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_LOCATION],
        );
    }
    
    public function getHiddenInputComponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_LOCATIONS:
                return [GD_Processor_SelectableLocationHiddenInputFormInputs::class, GD_Processor_SelectableLocationHiddenInputFormInputs::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTLOCATIONS];

            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_LOCATION:
                return [GD_Processor_SelectableLocationHiddenInputFormInputs::class, GD_Processor_SelectableLocationHiddenInputFormInputs::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTLOCATION];
        }

        return parent::getHiddenInputComponent($component);
    }

    public function isMultiple(\PoP\ComponentModel\Component\Component $component): bool
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_LOCATIONS:
                return true;
        }

        return parent::isMultiple($component);
    }
}



