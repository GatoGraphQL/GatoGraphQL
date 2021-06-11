<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;

class GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs extends PoP_Module_Processor_UserSelectableTypeaheadFormComponentsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS = 'filterinput-typeahead-communityplusmembers';
    public const MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST = 'filterinput-typeahead-communities-post';
    public const MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER = 'filterinput-typeahead-communities-user';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST],
            [self::class, self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
            [self::class, self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST => [GD_URE_Module_Processor_FilterInputProcessor::class, GD_URE_Module_Processor_FilterInputProcessor::URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST],
            self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER => [GD_URE_Module_Processor_FilterInputProcessor::class, GD_URE_Module_Processor_FilterInputProcessor::URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
            self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS => [GD_URE_Module_Processor_FilterInputProcessor::class, GD_URE_Module_Processor_FilterInputProcessor::URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER:
                return TranslationAPIFacade::getInstance()->__('Member of which Communities', 'ure-popprocessors');
        }

        return parent::getLabel($module, $props);
    }

    public function getInputSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST:
            case self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER:
                return [GD_UserCommunities_Module_Processor_TypeaheadTextFormInputs::class, GD_UserCommunities_Module_Processor_TypeaheadTextFormInputs::MODULE_FORMINPUT_TEXT_TYPEAHEADCOMMUNITIES];

            case self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS:
                return [PoP_Module_Processor_TypeaheadTextFormInputs::class, PoP_Module_Processor_TypeaheadTextFormInputs::MODULE_FORMINPUT_TEXT_TYPEAHEADPROFILES];
        }

        return parent::getInputSubmodule($module);
    }

    public function getComponentSubmodules(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST:
            case self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER:
                return array(
                    [GD_URE_Module_Processor_UserTypeaheadComponentFormInputs::class, GD_URE_Module_Processor_UserTypeaheadComponentFormInputs::MODULE_URE_TYPEAHEAD_COMPONENT_COMMUNITY],
                );

            case self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS:
                return array(
                    [GD_URE_Module_Processor_UserTypeaheadComponentFormInputs::class, GD_URE_Module_Processor_UserTypeaheadComponentFormInputs::MODULE_URE_TYPEAHEAD_COMPONENT_COMMUNITYPLUSMEMBERS],
                );
        }

        return parent::getComponentSubmodules($module);
    }

    public function getTriggerLayoutSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS:
                return [PoP_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITYUSERS];

            case self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST:
            case self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER:
                return [GD_URE_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::class, GD_URE_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_COMMUNITIES];
        }

        return parent::getTriggerLayoutSubmodule($module);
    }

    public function getSchemaFilterInputType(array $module): string
    {
        return match($module[1]) {
            self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST => SchemaDefinition::TYPE_ID,
            self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER => SchemaDefinition::TYPE_ID,
            self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS => SchemaDefinition::TYPE_ID,
            default => $this->getDefaultSchemaFilterInputType(),
        };
    }

    public function getSchemaFilterInputIsArrayType(array $module): bool
    {
        return match($module[1]) {
            self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST => true,
            self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER => true,
            self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS => true,
            default => false,
        };
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST => $translationAPI->__('', ''),
            self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER => $translationAPI->__('', ''),
            self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS => $translationAPI->__('', ''),
        ];
        return $descriptions[$module[1]] ?? null;
    }
}



