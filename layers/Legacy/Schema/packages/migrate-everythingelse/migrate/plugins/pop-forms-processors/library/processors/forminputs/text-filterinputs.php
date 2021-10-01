<?php
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor;
use PoPSchema\Users\FilterInputProcessors\FilterInputProcessor as UserFilterInputProcessor;

class PoP_Module_Processor_TextFilterInputs extends PoP_Module_Processor_TextFormInputsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_SEARCH = 'filterinput-search';
    public const MODULE_FILTERINPUT_HASHTAGS = 'filterinput-hashtags';
    public const MODULE_FILTERINPUT_NAME = 'filterinput-name';

    protected StringScalarTypeResolver $stringScalarTypeResolver;

    public function autowirePoP_Module_Processor_TextFilterInputs(
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_SEARCH],
            [self::class, self::MODULE_FILTERINPUT_HASHTAGS],
            [self::class, self::MODULE_FILTERINPUT_NAME],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_SEARCH => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_SEARCH],
            self::MODULE_FILTERINPUT_NAME => [UserFilterInputProcessor::class, UserFilterInputProcessor::FILTERINPUT_NAME],
            self::MODULE_FILTERINPUT_HASHTAGS => [PoP_Module_Processor_FormsFilterInputProcessor::class, PoP_Module_Processor_FormsFilterInputProcessor::FILTERINPUT_HASHTAGS],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERINPUT_SEARCH:
    //         case self::MODULE_FILTERINPUT_HASHTAGS:
    //         case self::MODULE_FILTERINPUT_NAME:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($module);
    // }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_SEARCH:
                return TranslationAPIFacade::getInstance()->__('Search', 'pop-coreprocessors');

            case self::MODULE_FILTERINPUT_HASHTAGS:
                return TranslationAPIFacade::getInstance()->__('Hashtags', 'pop-coreprocessors');

            case self::MODULE_FILTERINPUT_NAME:
                return TranslationAPIFacade::getInstance()->__('Name', 'pop-coreprocessors');
        }

        return parent::getLabelText($module, $props);
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_SEARCH:
            case self::MODULE_FILTERINPUT_HASHTAGS:
            case self::MODULE_FILTERINPUT_NAME:
                // Add a nice name, so that the URL params when filtering make sense
                $names = array(
                    self::MODULE_FILTERINPUT_SEARCH => 'searchfor',
                    self::MODULE_FILTERINPUT_HASHTAGS => 'tags',
                    self::MODULE_FILTERINPUT_NAME => 'nombre',
                );
                return $names[$module[1]];
        }

        return parent::getName($module);
    }

    public function getSchemaFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_SEARCH => $this->stringScalarTypeResolver,
            self::MODULE_FILTERINPUT_HASHTAGS => $this->stringScalarTypeResolver,
            self::MODULE_FILTERINPUT_NAME => $this->stringScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            self::MODULE_FILTERINPUT_SEARCH => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_HASHTAGS => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_NAME => $translationAPI->__('', ''),
        ];
        return $descriptions[$module[1]] ?? null;
    }
}



