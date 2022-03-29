<?php

class PoP_Module_Processor_UserSelectableTypeaheadTriggerFormComponents extends PoP_Module_Processor_UserTriggerLayoutFormComponentValuesBase
{
    public const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_AUTHORS = 'formcomponent-selectabletypeaheadtrigger-authors';
    public const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COAUTHORS = 'formcomponent-selectabletypeaheadtrigger-coauthors';
    public const MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_PROFILES = 'filtercomponent-selectableprofiles';
    public const MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITYUSERS = 'filtercomponent-communityusers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_AUTHORS],
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COAUTHORS],
            [self::class, self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_PROFILES],
            [self::class, self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITYUSERS],
        );
    }

    public function getTriggerSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_AUTHORS:
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COAUTHORS:
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_PROFILES:
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITYUSERS:
                $layouts = array(
                    self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_AUTHORS => [PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponents::class, PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_AUTHORS],
                    self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COAUTHORS => [PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponents::class, PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_COAUTHORS],
                    self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_PROFILES => [PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponents::class, PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponents::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_PROFILES],
                    self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITYUSERS => [PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponents::class, PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponents::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITYUSERS],
                );
                return $layouts[$module[1]];
        }

        return parent::getTriggerSubmodule($module);
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_AUTHORS:
                return 'authors';

            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COAUTHORS:
                return 'coauthors';
        }

        return parent::getDbobjectField($module);
    }
}



