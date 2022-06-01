<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_PostSelectableTypeaheadFormComponents extends PoP_Module_Processor_PostSelectableTypeaheadFormComponentsBase
{
    public final const COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES = 'formcomponent-selectabletypeahead-references';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return TranslationAPIFacade::getInstance()->__('Posted in response / as an addition to', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getInputSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return [PoP_Module_Processor_TypeaheadTextFormInputs::class, PoP_Module_Processor_TypeaheadTextFormInputs::COMPONENT_FORMINPUT_TEXT_TYPEAHEADRELATEDCONTENT];
        }

        return parent::getInputSubcomponent($component);
    }

    public function getComponentSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return array(
                    [PoP_Module_Processor_PostTypeaheadComponentFormInputs::class, PoP_Module_Processor_PostTypeaheadComponentFormInputs::COMPONENT_TYPEAHEAD_COMPONENT_CONTENT],
                );
        }

        return parent::getComponentSubcomponents($component);
    }
    
    public function getTriggerLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return [PoP_Module_Processor_PostSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_PostSelectableTypeaheadTriggerFormComponents::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES];
        }

        return parent::getTriggerLayoutSubcomponent($component);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return 'references';
        }
        
        return parent::getDbobjectField($component);
    }
}



