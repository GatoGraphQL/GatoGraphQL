<?php

class PoP_Module_Processor_UserSelectableTypeaheadTriggerFormComponents extends PoP_Module_Processor_UserTriggerLayoutFormComponentValuesBase
{
    public final const COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_AUTHORS = 'formcomponent-selectabletypeaheadtrigger-authors';
    public final const COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COAUTHORS = 'formcomponent-selectabletypeaheadtrigger-coauthors';
    public final const COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_PROFILES = 'filtercomponent-selectableprofiles';
    public final const COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITYUSERS = 'filtercomponent-communityusers';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_AUTHORS,
            self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COAUTHORS,
            self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_PROFILES,
            self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITYUSERS,
        );
    }

    public function getTriggerSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_AUTHORS:
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COAUTHORS:
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_PROFILES:
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITYUSERS:
                $layouts = array(
                    self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_AUTHORS => [PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponents::class, PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponents::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_AUTHORS],
                    self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COAUTHORS => [PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponents::class, PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponents::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_COAUTHORS],
                    self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_PROFILES => [PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponents::class, PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponents::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_PROFILES],
                    self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITYUSERS => [PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponents::class, PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponents::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITYUSERS],
                );
                return $layouts[$component->name];
        }

        return parent::getTriggerSubcomponent($component);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_AUTHORS:
                return 'authors';

            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COAUTHORS:
                return 'coauthors';
        }

        return parent::getDbobjectField($component);
    }
}



