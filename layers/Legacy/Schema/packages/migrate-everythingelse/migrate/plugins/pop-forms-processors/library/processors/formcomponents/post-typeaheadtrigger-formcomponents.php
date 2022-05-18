<?php

class PoP_Module_Processor_PostSelectableTypeaheadTriggerFormComponents extends PoP_Module_Processor_PostTriggerLayoutFormComponentValuesBase
{
    public final const COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES = 'formcomponent-selectabletypeaheadtrigger-references';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES],
        );
    }

    public function getTriggerSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES:
                $layouts = array(
                    self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES => [PoP_Module_Processor_PostSelectableTypeaheadAlertFormComponents::class, PoP_Module_Processor_PostSelectableTypeaheadAlertFormComponents::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_REFERENCES],
                );
                return $layouts[$component[1]];
        }

        return parent::getTriggerSubmodule($component);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES:
                return 'references';
        }

        return parent::getDbobjectField($component);
    }

    public function getUrlParam(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES:
                return POP_INPUTNAME_REFERENCES;
        }

        return parent::getUrlParam($component);
    }
}



