<?php
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use Symfony\Contracts\Service\Attribute\Required;

class PoPTheme_Wassup_Module_Processor_MultiSelectFilterInputs extends PoP_Module_Processor_BooleanMultiSelectFormInputsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT = 'filterinput-volunteersneededmulti';

    protected BooleanScalarTypeResolver $booleanScalarTypeResolver;

    #[Required]
    final public function autowirePoPTheme_Wassup_Module_Processor_MultiSelectFilterInputs(
        BooleanScalarTypeResolver $booleanScalarTypeResolver,
    ): void {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }

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
        return $filterInputs[$module[1]] ?? null;
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

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT:
                return GD_FormInput_MultiYesNo::class;
        }

        return parent::getInputClass($module);
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT:
                return 'volunteersneeded';
        }

        return parent::getName($module);
    }

    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT => $this->booleanScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $module): int
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT => SchemaTypeModifiers::IS_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT => $translationAPI->__('', ''),
            default => null,
        };
    }
}



