<?php
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;

class PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputs extends PoP_Module_Processor_MultiSelectFormInputsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_LINKCATEGORIES = 'filterinput-linkcategories';
    public const MODULE_FILTERINPUT_LINKACCESS = 'filterinput-linkaccess';

    protected IDScalarTypeResolver $idScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;

    public function autowirePoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputs(
        IDScalarTypeResolver $idScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

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

    public function getInputClass(array $module): string
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

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_LINKCATEGORIES:
                return 'categories';

            case self::MODULE_FILTERINPUT_LINKACCESS:
                return 'access';
        }

        return parent::getName($module);
    }

    public function getSchemaFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_LINKCATEGORIES => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_LINKACCESS => $this->stringScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getSchemaFilterInputTypeModifiers(array $module): int
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_LINKCATEGORIES => SchemaTypeModifiers::IS_ARRAY,
            default => 0,
        };
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_LINKCATEGORIES => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_LINKACCESS => $translationAPI->__('', ''),
            default => null,
        };
    }
}



