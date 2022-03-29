<?php

class PoP_Module_Processor_PostSelectableTypeaheadTriggerFormComponents extends PoP_Module_Processor_PostTriggerLayoutFormComponentValuesBase
{
    public const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES = 'formcomponent-selectabletypeaheadtrigger-references';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES],
        );
    }

    public function getTriggerSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES:
                $layouts = array(
                    self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES => [PoP_Module_Processor_PostSelectableTypeaheadAlertFormComponents::class, PoP_Module_Processor_PostSelectableTypeaheadAlertFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_REFERENCES],
                );
                return $layouts[$module[1]];
        }

        return parent::getTriggerSubmodule($module);
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES:
                return 'references';
        }

        return parent::getDbobjectField($module);
    }

    public function getUrlParam(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES:
                return POP_INPUTNAME_REFERENCES;
        }

        return parent::getUrlParam($module);
    }
}



