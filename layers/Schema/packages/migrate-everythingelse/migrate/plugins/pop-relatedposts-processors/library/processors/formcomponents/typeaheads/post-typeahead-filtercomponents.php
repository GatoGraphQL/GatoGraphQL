<?php

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\Schema\TypeCastingHelpers;

class PoP_Module_Processor_PostSelectableTypeaheadFilterComponents extends PoP_Module_Processor_PostSelectableTypeaheadFormComponentsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES = 'filtercomponent-selectabletypeahead-references';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES => [PoP_Module_Processor_ReferencesFilterInputProcessor::class, PoP_Module_Processor_ReferencesFilterInputProcessor::FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($module);
    // }

    public function getInputSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return [PoP_Module_Processor_TypeaheadTextFormInputs::class, PoP_Module_Processor_TypeaheadTextFormInputs::MODULE_FORMINPUT_TEXT_TYPEAHEADRELATEDCONTENT];
        }

        return parent::getInputSubmodule($module);
    }

    public function getComponentSubmodules(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return array(
                    [PoP_Module_Processor_PostTypeaheadComponentFormInputs::class, PoP_Module_Processor_PostTypeaheadComponentFormInputs::MODULE_TYPEAHEAD_COMPONENT_CONTENT],
                );
        }

        return parent::getComponentSubmodules($module);
    }

    public function getTriggerLayoutSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return [PoP_Module_Processor_PostSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_PostSelectableTypeaheadTriggerFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES];
        }

        return parent::getTriggerLayoutSubmodule($module);
    }

    public function getSchemaFilterInputType(array $module): ?string
    {
        $types = [
            self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
        ];
        return $types[$module[1]] ?? null;
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES => $translationAPI->__('', ''),
        ];
        return $descriptions[$module[1]] ?? null;
    }
}



