<?php

class GD_URE_Module_Processor_UserSelectableTypeaheadTriggerFormComponents extends PoP_Module_Processor_UserTriggerLayoutFormComponentValuesBase
{
    public final const COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_USERCOMMUNITIES = 'formcomponent-selectabletypeaheadtrigger-usercommunities';
    public final const COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITIES = 'filtercomponent-selectabletypeaheadtrigger-communities';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_USERCOMMUNITIES],
            [self::class, self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITIES],
        );
    }

    public function getTriggerSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_USERCOMMUNITIES:
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITIES:
                $layouts = array(
                    self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_USERCOMMUNITIES => [GD_URE_Module_Processor_UserSelectableTypeaheadAlertFormComponents::class, GD_URE_Module_Processor_UserSelectableTypeaheadAlertFormComponents::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_USERCOMMUNITIES],
                    self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITIES => [GD_URE_Module_Processor_UserSelectableTypeaheadAlertFormComponents::class, GD_URE_Module_Processor_UserSelectableTypeaheadAlertFormComponents::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITIES],
                );
                return $layouts[$component[1]];
        }

        return parent::getTriggerSubmodule($component);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_USERCOMMUNITIES:
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITIES:
                return 'communities';
        }

        return parent::getDbobjectField($component);
    }
}



