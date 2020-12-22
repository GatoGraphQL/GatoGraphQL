<?php

class GD_URE_Module_Processor_UserSelectableTypeaheadTriggerFormComponents extends PoP_Module_Processor_UserTriggerLayoutFormComponentValuesBase
{
    public const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_USERCOMMUNITIES = 'formcomponent-selectabletypeaheadtrigger-usercommunities';
    public const MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITIES = 'filtercomponent-selectabletypeaheadtrigger-communities';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_USERCOMMUNITIES],
            [self::class, self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITIES],
        );
    }

    public function getTriggerSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_USERCOMMUNITIES:
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITIES:
                $layouts = array(
                    self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_USERCOMMUNITIES => [GD_URE_Module_Processor_UserSelectableTypeaheadAlertFormComponents::class, GD_URE_Module_Processor_UserSelectableTypeaheadAlertFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_USERCOMMUNITIES],
                    self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITIES => [GD_URE_Module_Processor_UserSelectableTypeaheadAlertFormComponents::class, GD_URE_Module_Processor_UserSelectableTypeaheadAlertFormComponents::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITIES],
                );
                return $layouts[$module[1]];
        }

        return parent::getTriggerSubmodule($module);
    }

    public function getDbobjectField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_USERCOMMUNITIES:
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITIES:
                return 'communities';
        }

        return parent::getDbobjectField($module);
    }
}



