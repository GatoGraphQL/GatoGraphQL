<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\Schema\TypeCastingHelpers;

class PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputs extends PoP_Module_Processor_MultiSelectFormInputsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_LINKCATEGORIES = 'filterinput-linkcategories';
    public const MODULE_FILTERINPUT_LINKACCESS = 'filterinput-linkaccess';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_LINKCATEGORIES],
            [self::class, self::MODULE_FILTERINPUT_LINKACCESS],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_LINKCATEGORIES => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputProcessor::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputProcessor::FILTERINPUT_LINKCATEGORIES],
            self::MODULE_FILTERINPUT_LINKACCESS => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputProcessor::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputProcessor::FILTERINPUT_LINKACCESS],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERINPUT_LINKCATEGORIES:
    //         case self::MODULE_FILTERINPUT_LINKACCESS:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($module);
    // }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_LINKCATEGORIES:
                return TranslationAPIFacade::getInstance()->__('Categories', 'poptheme-wassup');

            case self::MODULE_FILTERINPUT_LINKACCESS:
                return TranslationAPIFacade::getInstance()->__('Access type', 'poptheme-wassup');
        }

        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_LINKCATEGORIES:
                return GD_FormInput_LinkCategories::class;

            case self::MODULE_FILTERINPUT_LINKACCESS:
                return GD_FormInput_LinkAccess::class;
        }

        return parent::getInputClass($module);
    }

    public function getDbobjectField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_LINKACCESS:
                return 'linkaccess';
        }

        return parent::getDbobjectField($module);
    }

    public function getName(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_LINKCATEGORIES:
                return 'categories';

            case self::MODULE_FILTERINPUT_LINKACCESS:
                return 'access';
        }

        return parent::getName($module);
    }

    public function getSchemaFilterInputType(array $module): string
    {
        $types = [
            self::MODULE_FILTERINPUT_LINKCATEGORIES => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            self::MODULE_FILTERINPUT_LINKACCESS => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$module[1]] ?? $this->getDefaultSchemaFilterInputType();
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            self::MODULE_FILTERINPUT_LINKCATEGORIES => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_LINKACCESS => $translationAPI->__('', ''),
        ];
        return $descriptions[$module[1]] ?? null;
    }
}



