<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts\ModuleProcessors\FormInputs;

use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\ModuleProcessors\AbstractFormInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\GenericCustomPosts\ComponentConfiguration;
use PoPSchema\GenericCustomPosts\FilterInputProcessors\FilterInputProcessor;

class FilterInputModuleProcessor extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES = 'filterinput-genericcustomposttypes';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_GENERICCUSTOMPOSTTYPES],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($module);
    }
    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES:
                $names = array(
                    self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => 'customPostTypes',
                );
                return $names[$module[1]];
        }

        return parent::getName($module);
    }

    public function getSchemaFilterInputTypeResolver(array $module): \PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => SchemaDefinition::TYPE_STRING,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getSchemaFilterInputIsArrayType(array $module): bool
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => true,
            default => false,
        };
    }

    public function getSchemaFilterInputIsNonNullableItemsInArrayType(array $module): bool
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => true,
            default => false,
        };
    }

    public function getSchemaFilterInputDefaultValue(array $module): mixed
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => ComponentConfiguration::getGenericCustomPostTypes(),
            default => null,
        };
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $descriptions = [
            self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => $this->translationAPI->__('Return results from Custom Post Types', 'customposts'),
        ];
        return $descriptions[$module[1]] ?? null;
    }
}
