<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_PostSelectableTypeaheadFormComponents extends PoP_Module_Processor_PostSelectableTypeaheadFormComponentsBase
{
    public final const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES = 'formcomponent-selectabletypeahead-references';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return TranslationAPIFacade::getInstance()->__('Posted in response / as an addition to', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getInputSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return [PoP_Module_Processor_TypeaheadTextFormInputs::class, PoP_Module_Processor_TypeaheadTextFormInputs::MODULE_FORMINPUT_TEXT_TYPEAHEADRELATEDCONTENT];
        }

        return parent::getInputSubmodule($component);
    }

    public function getComponentSubmodules(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return array(
                    [PoP_Module_Processor_PostTypeaheadComponentFormInputs::class, PoP_Module_Processor_PostTypeaheadComponentFormInputs::MODULE_TYPEAHEAD_COMPONENT_CONTENT],
                );
        }

        return parent::getComponentSubmodules($component);
    }
    
    public function getTriggerLayoutSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return [PoP_Module_Processor_PostSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_PostSelectableTypeaheadTriggerFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES];
        }

        return parent::getTriggerLayoutSubmodule($component);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return 'references';
        }
        
        return parent::getDbobjectField($component);
    }
}



