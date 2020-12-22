<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\Schema\TypeCastingHelpers;

class PoPTheme_Wassup_Module_Processor_MultiSelectFilterInputs extends PoP_Module_Processor_BooleanMultiSelectFormInputsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT = 'filterinput-volunteersneededmulti';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT => [PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::class, PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::FILTERINPUT_VOLUNTEERSNEEDED],
        ];
        return $filterInputs[$module[1]];
    }

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($module);
    // }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT:
                return TranslationAPIFacade::getInstance()->__('Volunteers Needed?', 'poptheme-wassup');
        }

        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT:
                return GD_FormInput_MultiYesNo::class;
        }

        return parent::getInputClass($module);
    }

    public function getName(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT:
                return 'volunteersneeded';
        }

        return parent::getName($module);
    }

    public function getSchemaFilterInputType(array $module): ?string
    {
        $types = [
            self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_BOOL),
        ];
        return $types[$module[1]];
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT => $translationAPI->__('', ''),
        ];
        return $descriptions[$module[1]];
    }
}



