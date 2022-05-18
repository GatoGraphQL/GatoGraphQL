<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs extends PoP_Module_Processor_UserSelectableTypeaheadFormComponentsBase
{
    public final const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS = 'forminput-selectabletypeahead-postauthors';
    public final const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS = 'forminput-selectabletypeahead-postcoauthors';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS],
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS:
                return TranslationAPIFacade::getInstance()->__('Author(s)', 'pop-coreprocessors');

            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                return TranslationAPIFacade::getInstance()->__('Co-authors', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function getInputSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS:
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                return [GD_CAP_Module_Processor_TypeaheadTextFormInputs::class, GD_CAP_Module_Processor_TypeaheadTextFormInputs::MODULE_FORMINPUT_TEXT_TYPEAHEADPOSTAUTHORS];
        }

        return parent::getInputSubmodule($componentVariation);
    }

    public function getComponentSubmodules(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS:
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                return array(
                    [PoP_Module_Processor_UserTypeaheadComponentFormInputs::class, PoP_Module_Processor_UserTypeaheadComponentFormInputs::MODULE_TYPEAHEAD_COMPONENT_USERS],
                );
        }

        return parent::getComponentSubmodules($componentVariation);
    }

    public function getTriggerLayoutSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS:
                return [PoP_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_AUTHORS];

            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                return [PoP_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COAUTHORS];
        }

        return parent::getTriggerLayoutSubmodule($componentVariation);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS:
                return 'authors';

            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                return 'coauthors';
        }
        
        return parent::getDbobjectField($componentVariation);
    }
}



