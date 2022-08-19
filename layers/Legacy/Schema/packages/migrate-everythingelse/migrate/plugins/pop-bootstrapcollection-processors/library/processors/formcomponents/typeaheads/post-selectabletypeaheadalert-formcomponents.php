<?php

class PoP_Module_Processor_PostSelectableTypeaheadAlertFormComponents extends PoP_Module_Processor_PostSelectableTypeaheadAlertFormComponentsBase
{
    public final const COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_REFERENCES = 'formcomponent-selectabletypeaheadalert-references';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_REFERENCES,
        );
    }
    
    public function getHiddenInputComponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_REFERENCES:
                return [GD_Processor_SelectableHiddenInputFormInputs::class, GD_Processor_SelectableHiddenInputFormInputs::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLEREFERENCES];
        }

        return parent::getHiddenInputComponent($component);
    }

    public function isMultiple(\PoP\ComponentModel\Component\Component $component): bool
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_REFERENCES:
                return true;
        }

        return parent::isMultiple($component);
    }
}



