<?php

class GD_URE_Module_Processor_UserSelectableTypeaheadTriggerFormComponents extends PoP_Module_Processor_UserTriggerLayoutFormComponentValuesBase
{
    public final const COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_USERCOMMUNITIES = 'formcomponent-selectabletypeaheadtrigger-usercommunities';
    public final const COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITIES = 'filtercomponent-selectabletypeaheadtrigger-communities';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_USERCOMMUNITIES,
            self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITIES,
        );
    }

    public function getTriggerSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_USERCOMMUNITIES:
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITIES:
                $layouts = array(
                    self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_USERCOMMUNITIES => [GD_URE_Module_Processor_UserSelectableTypeaheadAlertFormComponents::class, GD_URE_Module_Processor_UserSelectableTypeaheadAlertFormComponents::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_USERCOMMUNITIES],
                    self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITIES => [GD_URE_Module_Processor_UserSelectableTypeaheadAlertFormComponents::class, GD_URE_Module_Processor_UserSelectableTypeaheadAlertFormComponents::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITIES],
                );
                return $layouts[$component->name];
        }

        return parent::getTriggerSubcomponent($component);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_USERCOMMUNITIES:
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITIES:
                return 'communities';
        }

        return parent::getDbobjectField($component);
    }
}



