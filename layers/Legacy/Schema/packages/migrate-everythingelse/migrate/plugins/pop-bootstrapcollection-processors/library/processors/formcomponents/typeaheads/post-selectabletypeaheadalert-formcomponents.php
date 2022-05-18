<?php

class PoP_Module_Processor_PostSelectableTypeaheadAlertFormComponents extends PoP_Module_Processor_PostSelectableTypeaheadAlertFormComponentsBase
{
    public final const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_REFERENCES = 'formcomponent-selectabletypeaheadalert-references';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_REFERENCES],
        );
    }
    
    public function getHiddeninputModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_REFERENCES:
                return [GD_Processor_SelectableHiddenInputFormInputs::class, GD_Processor_SelectableHiddenInputFormInputs::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLEREFERENCES];
        }

        return parent::getHiddeninputModule($component);
    }

    public function isMultiple(array $component): bool
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_REFERENCES:
                return true;
        }

        return parent::isMultiple($component);
    }
}



