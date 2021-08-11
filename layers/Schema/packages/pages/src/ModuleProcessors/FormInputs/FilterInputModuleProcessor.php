<?php

declare(strict_types=1);

namespace PoPSchema\Pages\ModuleProcessors\FormInputs;

use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\ModuleProcessors\AbstractFormInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\Pages\FilterInputProcessors\FilterInputProcessor;

class FilterInputModuleProcessor extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_PARENT_IDS = 'filterinput-parent-ids';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_PARENT_IDS],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_PARENT_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_PARENT_IDS],
            default => null,
        };
    }

    public function getInputClass(array $module): string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_PARENT_IDS => FormMultipleInput::class,
            default =>parent::getInputClass($module),
        };
    }

    public function getName(array $module): string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_PARENT_IDS => 'parentIDs',
            default => parent::getName($module),
        };
    }

    public function getSchemaFilterInputType(array $module): string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_PARENT_IDS => SchemaDefinition::TYPE_ID,
            default => $this->getDefaultSchemaFilterInputType(),
        };
    }

    public function getSchemaFilterInputIsArrayType(array $module): bool
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_PARENT_IDS => true,
            default => false,
        };
    }

    public function getSchemaFilterInputIsNonNullableItemsInArrayType(array $module): bool
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_PARENT_IDS => true,
            default => false,
        };
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_PARENT_IDS => $this->translationAPI->__('Limit results to elements with the given page parent IDs', 'pages'),
            default => null,
        };
    }
}
