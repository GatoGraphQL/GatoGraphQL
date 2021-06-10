<?php
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\Schema\TypeCastingHelpers;

class PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs extends PoP_Module_Processor_ButtonGroupFormInputsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES = 'filterinput-buttongroup-categories';
    public const MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS = 'filterinput-buttongroup-contentsections';
    public const MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS = 'filterinput-buttongroup-postsections';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES],
            [self::class, self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS],
            [self::class, self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES => [PoP_Module_Processor_UserPlatformFilterInputProcessor::class, PoP_Module_Processor_UserPlatformFilterInputProcessor::FILTERINPUT_BUTTONGROUP_CATEGORIES],
            self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS => [PoP_Module_Processor_UserPlatformFilterInputProcessor::class, PoP_Module_Processor_UserPlatformFilterInputProcessor::FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS],
            self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS => [PoP_Module_Processor_UserPlatformFilterInputProcessor::class, PoP_Module_Processor_UserPlatformFilterInputProcessor::FILTERINPUT_BUTTONGROUP_POSTSECTIONS],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES:
    //         case self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS:
    //         case self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($module);
    // }

    public function getName(array $module)
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES:
            case self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS:
            case self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS:
                // Return the name of the other Categories input (the multiselect), so we can filter by this input using the DelegatorFilter pretending to be the other one
                $inputs = array(
                    self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES => [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_CATEGORIES],
                    self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS => [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_CONTENTSECTIONS],
                    self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS => [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_POSTSECTIONS],
                );
                $input = $inputs[$module[1]];
                return $moduleprocessor_manager->getProcessor($input)->getName($input);
        }

        return parent::getName($module);
    }

    public function getInputClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES:
                return GD_FormInput_Categories::class;

            case self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS:
                return GD_FormInput_ContentSections::class;

            case self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS:
                return GD_FormInput_PostSections::class;
        }

        return parent::getInputClass($module);
    }

    public function isMultiple(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES:
            case self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS:
            case self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS:
                return true;
        }

        return parent::isMultiple($module);
    }

    public function getSchemaFilterInputType(array $module): string
    {
        $types = [
            self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
        ];
        return $types[$module[1]] ?? $this->getDefaultSchemaFilterInputType();
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS => $translationAPI->__('', ''),
        ];
        return $descriptions[$module[1]] ?? null;
    }
}



