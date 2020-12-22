<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\Schema\TypeCastingHelpers;

class GD_URE_Module_Processor_MultiSelectFilterInputs extends PoP_Module_Processor_MultiSelectFormInputsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS = 'filterinput-individualinterests';
    public const MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES = 'filterinput-organizationcategories';
    public const MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES = 'filterinput-organizationtypes';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS],
            [self::class, self::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES],
            [self::class, self::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS => [GD_URE_Module_Processor_MultiSelectFilterInputProcessor::class, GD_URE_Module_Processor_MultiSelectFilterInputProcessor::URE_FILTERINPUT_INDIVIDUALINTERESTS],
            self::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES => [GD_URE_Module_Processor_MultiSelectFilterInputProcessor::class, GD_URE_Module_Processor_MultiSelectFilterInputProcessor::URE_FILTERINPUT_ORGANIZATIONCATEGORIES],
            self::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES => [GD_URE_Module_Processor_MultiSelectFilterInputProcessor::class, GD_URE_Module_Processor_MultiSelectFilterInputProcessor::URE_FILTERINPUT_ORGANIZATIONTYPES],
        ];
        return $filterInputs[$module[1]];
    }

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS:
    //         case self::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES:
    //         case self::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($module);
    // }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS:
                return TranslationAPIFacade::getInstance()->__('Interests', 'poptheme-wassup');

            case self::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES:
                // Allow AgendaUrbana to Override
                return HooksAPIFacade::getInstance()->applyFilters(
                    'GD_URE_Module_Processor_MultiSelectFormInputs:label:categories',
                    TranslationAPIFacade::getInstance()->__('Organization Categories', 'poptheme-wassup')
                );

            case self::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES:
                // Allow AgendaUrbana to Override
                return HooksAPIFacade::getInstance()->applyFilters(
                    'GD_URE_Module_Processor_MultiSelectFormInputs:label:types',
                    TranslationAPIFacade::getInstance()->__('Organization Types', 'poptheme-wassup')
                );
        }

        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS:
                return GD_FormInput_IndividualInterests::class;

            case self::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES:
                return GD_FormInput_OrganizationCategories::class;

            case self::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES:
                return GD_FormInput_OrganizationTypes::class;
        }

        return parent::getInputClass($module);
    }

    public function getName(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS:
                return 'interests';

            case self::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES:
                return 'categories';

            case self::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES:
                return 'types';
        }

        return parent::getName($module);
    }

    public function getSchemaFilterInputType(array $module): ?string
    {
        $types = [
            self::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            self::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            self::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
        ];
        return $types[$module[1]];
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            self::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS => $translationAPI->__('', ''),
            self::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES => $translationAPI->__('', ''),
            self::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES => $translationAPI->__('', ''),
        ];
        return $descriptions[$module[1]];
    }
}



