<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_UserSelectableTypeaheadFormInputs extends PoP_Module_Processor_UserSelectableTypeaheadFormComponentsBase
{
    public final const COMPONENT_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES = 'forminput-selectabletypeahead-ure-communities';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES],
        );
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
                return 'communities';
        }
        
        return parent::getDbobjectField($component);
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
                return TranslationAPIFacade::getInstance()->__('Are you member of any community? Select them here.', 'ure-popprocessors');
        }
        
        return parent::getLabel($component, $props);
    }

    public function getInputSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
                return [GD_UserCommunities_Module_Processor_TypeaheadTextFormInputs::class, GD_UserCommunities_Module_Processor_TypeaheadTextFormInputs::COMPONENT_FORMINPUT_TEXT_TYPEAHEADCOMMUNITIES];
        }

        return parent::getInputSubcomponent($component);
    }

    public function getComponentSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
                return array(
                    [GD_URE_Module_Processor_UserTypeaheadComponentFormInputs::class, GD_URE_Module_Processor_UserTypeaheadComponentFormInputs::COMPONENT_URE_TYPEAHEAD_COMPONENT_COMMUNITY],
                );
        }

        return parent::getComponentSubcomponents($component);
    }

    public function getTriggerLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
                return [GD_URE_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::class, GD_URE_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_USERCOMMUNITIES];
        }

        return parent::getTriggerLayoutSubcomponent($component);
    }
}



