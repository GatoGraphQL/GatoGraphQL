<?php
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;

class UserStance_URE_Module_Processor_MultiSelectFilterInputs extends PoP_Module_Processor_MultiSelectFormInputsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_AUTHORROLE_MULTISELECT = 'filterinput-multiselect-authorrole';

    protected \PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver $idScalarTypeResolver;
    protected \PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver;

    public function autowireUserStance_URE_Module_Processor_MultiSelectFilterInputs(
        \PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver $idScalarTypeResolver,
        \PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_AUTHORROLE_MULTISELECT],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_AUTHORROLE_MULTISELECT => [PoP_Module_Processor_UserStanceUserRolesFilterInputProcessor::class, PoP_Module_Processor_UserStanceUserRolesFilterInputProcessor::FILTERINPUT_AUTHORROLE_MULTISELECT],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERINPUT_AUTHORROLE_MULTISELECT:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($module);
    // }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_AUTHORROLE_MULTISELECT:
                return TranslationAPIFacade::getInstance()->__('Author Role', 'pop-userstance-processors');
        }

        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_AUTHORROLE_MULTISELECT:
                return GD_URE_FormInput_MultiAuthorRole::class;
        }

        return parent::getInputClass($module);
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_AUTHORROLE_MULTISELECT:
                return 'role';
        }

        return parent::getName($module);
    }

    public function getSchemaFilterInputTypeResolver(array $module): \PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_AUTHORROLE_MULTISELECT => $this->stringScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getSchemaFilterInputIsArrayType(array $module): bool
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_AUTHORROLE_MULTISELECT => true,
            default => false,
        };
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            self::MODULE_FILTERINPUT_AUTHORROLE_MULTISELECT => $translationAPI->__('', ''),
        ];
        return $descriptions[$module[1]] ?? null;
    }
}



