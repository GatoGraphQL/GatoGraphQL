<?php
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs extends PoP_Module_Processor_MultiSelectFormInputsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_APPLIESTO = 'filterinput-appliesto';
    public const MODULE_FILTERINPUT_CATEGORIES = 'filterinput-categories';
    public const MODULE_FILTERINPUT_CONTENTSECTIONS = 'filterinput-contentsections';
    public const MODULE_FILTERINPUT_POSTSECTIONS = 'filterinput-postsections';

    protected IDScalarTypeResolver $idScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;

    public function autowirePoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs(
        IDScalarTypeResolver $idScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_APPLIESTO],
            [self::class, self::MODULE_FILTERINPUT_CATEGORIES],
            [self::class, self::MODULE_FILTERINPUT_CONTENTSECTIONS],
            [self::class, self::MODULE_FILTERINPUT_POSTSECTIONS],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_POSTSECTIONS => [PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::class, PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::FILTERINPUT_POSTSECTIONS],
            self::MODULE_FILTERINPUT_CATEGORIES => [PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::class, PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::FILTERINPUT_CATEGORIES],
            self::MODULE_FILTERINPUT_CONTENTSECTIONS => [PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::class, PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::FILTERINPUT_CONTENTSECTIONS],
            self::MODULE_FILTERINPUT_APPLIESTO => [PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::class, PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::FILTERINPUT_APPLIESTO],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERINPUT_CATEGORIES:
    //         case self::MODULE_FILTERINPUT_CONTENTSECTIONS:
    //         case self::MODULE_FILTERINPUT_POSTSECTIONS:
    //         case self::MODULE_FILTERINPUT_APPLIESTO:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($module);
    // }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CONTENTSECTIONS:
            case self::MODULE_FILTERINPUT_POSTSECTIONS:
                return TranslationAPIFacade::getInstance()->__('Sections', 'poptheme-wassup');

            case self::MODULE_FILTERINPUT_APPLIESTO:
                return TranslationAPIFacade::getInstance()->__('Applies to', 'poptheme-wassup');
        }

        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CATEGORIES:
                return GD_FormInput_Categories::class;

            case self::MODULE_FILTERINPUT_CONTENTSECTIONS:
                return GD_FormInput_ContentSections::class;

            case self::MODULE_FILTERINPUT_POSTSECTIONS:
                return GD_FormInput_PostSections::class;

            case self::MODULE_FILTERINPUT_APPLIESTO:
                return GD_FormInput_AppliesTo::class;
        }

        return parent::getInputClass($module);
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CATEGORIES:
                return 'appliesto';

            case self::MODULE_FILTERINPUT_CONTENTSECTIONS:
                return 'categories';

            case self::MODULE_FILTERINPUT_POSTSECTIONS:
            case self::MODULE_FILTERINPUT_APPLIESTO:
                return 'sections';
        }

        return parent::getName($module);
    }

    public function getSchemaFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_APPLIESTO => $this->stringScalarTypeResolver,
            self::MODULE_FILTERINPUT_CATEGORIES => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_CONTENTSECTIONS => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_POSTSECTIONS => $this->idScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getSchemaFilterInputIsArrayType(array $module): bool
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_APPLIESTO => true,
            self::MODULE_FILTERINPUT_CATEGORIES => true,
            self::MODULE_FILTERINPUT_CONTENTSECTIONS => true,
            self::MODULE_FILTERINPUT_POSTSECTIONS => true,
            default => false,
        };
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            self::MODULE_FILTERINPUT_APPLIESTO => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_CATEGORIES => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_CONTENTSECTIONS => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_POSTSECTIONS => $translationAPI->__('', ''),
        ];
        return $descriptions[$module[1]] ?? null;
    }
}



