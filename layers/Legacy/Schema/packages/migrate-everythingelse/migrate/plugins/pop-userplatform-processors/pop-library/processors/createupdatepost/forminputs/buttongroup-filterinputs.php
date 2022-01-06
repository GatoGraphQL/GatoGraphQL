<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use Symfony\Contracts\Service\Attribute\Required;

class PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs extends PoP_Module_Processor_ButtonGroupFormInputsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES = 'filterinput-buttongroup-categories';
    public const MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS = 'filterinput-buttongroup-contentsections';
    public const MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS = 'filterinput-buttongroup-postsections';

    private ?IDScalarTypeResolver $idScalarTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }

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

    public function getName(array $module): string
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

    public function getInputClass(array $module): string
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

    public function isMultiple(array $module): bool
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES:
            case self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS:
            case self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS:
                return true;
        }

        return parent::isMultiple($module);
    }

    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS => $this->idScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $module): int
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES,
            self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS,
            self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS => $translationAPI->__('', ''),
            default => null,
        };
    }
}



