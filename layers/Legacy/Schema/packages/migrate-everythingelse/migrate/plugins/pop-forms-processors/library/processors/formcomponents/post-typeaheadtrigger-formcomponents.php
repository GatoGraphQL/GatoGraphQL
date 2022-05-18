<?php

class PoP_Module_Processor_PostSelectableTypeaheadTriggerFormComponents extends PoP_Module_Processor_PostTriggerLayoutFormComponentValuesBase
{
    public final const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES = 'formcomponent-selectabletypeaheadtrigger-references';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES],
        );
    }

    public function getTriggerSubmodule(array $componentVariation): ?array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES:
                $layouts = array(
                    self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES => [PoP_Module_Processor_PostSelectableTypeaheadAlertFormComponents::class, PoP_Module_Processor_PostSelectableTypeaheadAlertFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_REFERENCES],
                );
                return $layouts[$componentVariation[1]];
        }

        return parent::getTriggerSubmodule($componentVariation);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES:
                return 'references';
        }

        return parent::getDbobjectField($componentVariation);
    }

    public function getUrlParam(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES:
                return POP_INPUTNAME_REFERENCES;
        }

        return parent::getUrlParam($componentVariation);
    }
}



