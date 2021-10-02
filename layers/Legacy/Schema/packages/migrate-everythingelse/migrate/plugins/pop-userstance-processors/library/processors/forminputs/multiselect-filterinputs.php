<?php
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;

class UserStance_Module_Processor_MultiSelectFilterInputs extends PoP_Module_Processor_MultiSelectFormInputsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_STANCE_MULTISELECT = 'filterinput-multiselect-stance';

    protected IDScalarTypeResolver $idScalarTypeResolver;

    public function autowireUserStance_Module_Processor_MultiSelectFilterInputs(
        IDScalarTypeResolver $idScalarTypeResolver,
    ): void {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_STANCE_MULTISELECT],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_STANCE_MULTISELECT => [PoP_Module_Processor_UserStanceFilterInputProcessor::class, PoP_Module_Processor_UserStanceFilterInputProcessor::FILTERINPUT_STANCE_MULTISELECT],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERINPUT_STANCE_MULTISELECT:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($module);
    // }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_STANCE_MULTISELECT:
                return TranslationAPIFacade::getInstance()->__('Stance', 'pop-userstance-processors');
        }

        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_STANCE_MULTISELECT:
                return GD_FormInput_MultiStance::class;
        }

        return parent::getInputClass($module);
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_STANCE_MULTISELECT:
                return 'stance';
        }

        return parent::getName($module);
    }

    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_STANCE_MULTISELECT => $this->idScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $module): int
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_STANCE_MULTISELECT => SchemaTypeModifiers::IS_ARRAY,
            default => 0,
        };
    }

    public function getFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_STANCE_MULTISELECT => $translationAPI->__('', ''),
            default => null,
        };
    }
}



