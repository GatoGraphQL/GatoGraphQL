<?php

declare(strict_types=1);

namespace PoPSchema\Categories\ModuleProcessors\FormInputs;

use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\ModuleProcessors\AbstractFormInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\Categories\FilterInputProcessors\FilterInputProcessor;

class FilterInputModuleProcessor extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_CATEGORY_IDS = 'filterinput-category-ids';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_CATEGORY_IDS],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_CATEGORY_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_CATEGORY_IDS],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CATEGORY_IDS:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($module);
    }

    public function getName(array $module): string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CATEGORY_IDS => 'categoryIDs',
            default => parent::getName($module),
        };
    }

    public function getSchemaFilterInputType(array $module): string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CATEGORY_IDS => SchemaDefinition::TYPE_ID,
            default => $this->getDefaultSchemaFilterInputType(),
        };
    }

    public function getSchemaFilterInputIsArrayType(array $module): bool
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CATEGORY_IDS => true,
            default => false,
        };
    }

    public function getSchemaFilterInputIsNonNullableItemsInArrayType(array $module): bool
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CATEGORY_IDS => true,
            default => false,
        };
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CATEGORY_IDS => $this->translationAPI->__('Limit results to elements with the given ids', 'categories'),
            default => null,
        };
    }
}
