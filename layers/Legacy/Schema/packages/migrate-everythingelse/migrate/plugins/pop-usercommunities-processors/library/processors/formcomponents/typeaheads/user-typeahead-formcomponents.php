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

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
                return 'communities';
        }
        
        return parent::getDbobjectField($component);
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
                return TranslationAPIFacade::getInstance()->__('Are you member of any community? Select them here.', 'ure-popprocessors');
        }
        
        return parent::getLabel($component, $props);
    }

    public function getInputSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
                return [GD_UserCommunities_Module_Processor_TypeaheadTextFormInputs::class, GD_UserCommunities_Module_Processor_TypeaheadTextFormInputs::COMPONENT_FORMINPUT_TEXT_TYPEAHEADCOMMUNITIES];
        }

        return parent::getInputSubmodule($component);
    }

    public function getComponentSubmodules(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
                return array(
                    [GD_URE_Module_Processor_UserTypeaheadComponentFormInputs::class, GD_URE_Module_Processor_UserTypeaheadComponentFormInputs::COMPONENT_URE_TYPEAHEAD_COMPONENT_COMMUNITY],
                );
        }

        return parent::getComponentSubmodules($component);
    }

    public function getTriggerLayoutSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
                return [GD_URE_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::class, GD_URE_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_USERCOMMUNITIES];
        }

        return parent::getTriggerLayoutSubmodule($component);
    }
}



