<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs extends PoP_Module_Processor_UserSelectableTypeaheadFormComponentsBase
{
    public final const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS = 'forminput-selectabletypeahead-postauthors';
    public final const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS = 'forminput-selectabletypeahead-postcoauthors';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS],
            [self::class, self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS:
                return TranslationAPIFacade::getInstance()->__('Author(s)', 'pop-coreprocessors');

            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                return TranslationAPIFacade::getInstance()->__('Co-authors', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getInputSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS:
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                return [GD_CAP_Module_Processor_TypeaheadTextFormInputs::class, GD_CAP_Module_Processor_TypeaheadTextFormInputs::COMPONENT_FORMINPUT_TEXT_TYPEAHEADPOSTAUTHORS];
        }

        return parent::getInputSubmodule($component);
    }

    public function getComponentSubmodules(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS:
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                return array(
                    [PoP_Module_Processor_UserTypeaheadComponentFormInputs::class, PoP_Module_Processor_UserTypeaheadComponentFormInputs::COMPONENT_TYPEAHEAD_COMPONENT_USERS],
                );
        }

        return parent::getComponentSubmodules($component);
    }

    public function getTriggerLayoutSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS:
                return [PoP_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_AUTHORS];

            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                return [PoP_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COAUTHORS];
        }

        return parent::getTriggerLayoutSubmodule($component);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS:
                return 'authors';

            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                return 'coauthors';
        }
        
        return parent::getDbobjectField($component);
    }
}



