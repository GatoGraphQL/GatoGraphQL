<?php

class PoP_Module_Processor_LocationSelectableTypeaheadTriggerFormComponents extends PoP_Module_Processor_LocationTriggerLayoutFormComponentValuesBase
{
    public final const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_LOCATIONS = 'formcomponent-selectabletypeaheadtrigger-locations';
    public final const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_LOCATION = 'formcomponent-selectabletypeaheadtrigger-location';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_LOCATIONS],
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_LOCATION],
        );
    }

    public function getTriggerSubmodule(array $componentVariation): ?array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_LOCATIONS:
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_LOCATION:
                $layouts = array(
                    self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_LOCATIONS => [PoP_Module_Processor_LocationSelectableTypeaheadAlertFormComponents::class, PoP_Module_Processor_LocationSelectableTypeaheadAlertFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_LOCATIONS],
                    self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_LOCATION => [PoP_Module_Processor_LocationSelectableTypeaheadAlertFormComponents::class, PoP_Module_Processor_LocationSelectableTypeaheadAlertFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_LOCATION],
                );
                return $layouts[$componentVariation[1]];
        }

        return parent::getTriggerSubmodule($componentVariation);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_LOCATIONS:
                return 'locations';

            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_LOCATION:
                return 'location';
        }

        return parent::getDbobjectField($componentVariation);
    }
}



