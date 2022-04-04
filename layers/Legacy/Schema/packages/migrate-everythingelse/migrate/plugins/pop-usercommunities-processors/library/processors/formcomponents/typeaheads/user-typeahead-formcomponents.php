<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_UserSelectableTypeaheadFormInputs extends PoP_Module_Processor_UserSelectableTypeaheadFormComponentsBase
{
    public final const MODULE_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES = 'forminput-selectabletypeahead-ure-communities';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES],
        );
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
                return 'communities';
        }
        
        return parent::getDbobjectField($module);
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
                return TranslationAPIFacade::getInstance()->__('Are you member of any community? Select them here.', 'ure-popprocessors');
        }
        
        return parent::getLabel($module, $props);
    }

    public function getInputSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
                return [GD_UserCommunities_Module_Processor_TypeaheadTextFormInputs::class, GD_UserCommunities_Module_Processor_TypeaheadTextFormInputs::MODULE_FORMINPUT_TEXT_TYPEAHEADCOMMUNITIES];
        }

        return parent::getInputSubmodule($module);
    }

    public function getComponentSubmodules(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
                return array(
                    [GD_URE_Module_Processor_UserTypeaheadComponentFormInputs::class, GD_URE_Module_Processor_UserTypeaheadComponentFormInputs::MODULE_URE_TYPEAHEAD_COMPONENT_COMMUNITY],
                );
        }

        return parent::getComponentSubmodules($module);
    }

    public function getTriggerLayoutSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
                return [GD_URE_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::class, GD_URE_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_USERCOMMUNITIES];
        }

        return parent::getTriggerLayoutSubmodule($module);
    }
}



