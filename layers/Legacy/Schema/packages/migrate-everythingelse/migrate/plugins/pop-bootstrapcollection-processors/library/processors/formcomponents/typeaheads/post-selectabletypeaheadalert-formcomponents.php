<?php

class PoP_Module_Processor_PostSelectableTypeaheadAlertFormComponents extends PoP_Module_Processor_PostSelectableTypeaheadAlertFormComponentsBase
{
    public final const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_REFERENCES = 'formcomponent-selectabletypeaheadalert-references';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_REFERENCES],
        );
    }
    
    public function getHiddeninputModule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_REFERENCES:
                return [GD_Processor_SelectableHiddenInputFormInputs::class, GD_Processor_SelectableHiddenInputFormInputs::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLEREFERENCES];
        }

        return parent::getHiddeninputModule($componentVariation);
    }

    public function isMultiple(array $componentVariation): bool
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_REFERENCES:
                return true;
        }

        return parent::isMultiple($componentVariation);
    }
}



