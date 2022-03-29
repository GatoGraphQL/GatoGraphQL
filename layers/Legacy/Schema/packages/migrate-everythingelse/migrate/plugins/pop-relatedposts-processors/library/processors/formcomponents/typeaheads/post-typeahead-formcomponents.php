<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_PostSelectableTypeaheadFormComponents extends PoP_Module_Processor_PostSelectableTypeaheadFormComponentsBase
{
    public const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES = 'formcomponent-selectabletypeahead-references';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return TranslationAPIFacade::getInstance()->__('Posted in response / as an addition to', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function getInputSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return [PoP_Module_Processor_TypeaheadTextFormInputs::class, PoP_Module_Processor_TypeaheadTextFormInputs::MODULE_FORMINPUT_TEXT_TYPEAHEADRELATEDCONTENT];
        }

        return parent::getInputSubmodule($module);
    }

    public function getComponentSubmodules(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return array(
                    [PoP_Module_Processor_PostTypeaheadComponentFormInputs::class, PoP_Module_Processor_PostTypeaheadComponentFormInputs::MODULE_TYPEAHEAD_COMPONENT_CONTENT],
                );
        }

        return parent::getComponentSubmodules($module);
    }
    
    public function getTriggerLayoutSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return [PoP_Module_Processor_PostSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_PostSelectableTypeaheadTriggerFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES];
        }

        return parent::getTriggerLayoutSubmodule($module);
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return 'references';
        }
        
        return parent::getDbobjectField($module);
    }
}



