<?php

declare(strict_types=1);

namespace PoPSchema\Comments\ModuleProcessors\FormInputs;

use PoPSchema\Comments\FilterInputProcessors\FilterInputProcessor;
use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\ModuleProcessors\AbstractFormInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;

class FilterInputModuleProcessor extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_CUSTOMPOST_IDS = 'filterinput-custompost-ids';
    public const MODULE_FILTERINPUT_CUSTOMPOST_ID = 'filterinput-custompost-id';
    public const MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS = 'filterinput-exclude-custompost-ids';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_CUSTOMPOST_IDS],
            [self::class, self::MODULE_FILTERINPUT_CUSTOMPOST_ID],
            [self::class, self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_CUSTOMPOST_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_CUSTOMPOST_IDS],
            self::MODULE_FILTERINPUT_CUSTOMPOST_ID => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_CUSTOMPOST_ID],
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CUSTOMPOST_IDS:
            case self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($module);
    }

    public function getName(array $module): string
    {
        // Add a nice name, so that the URL params when filtering make sense
        $names = array(
            self::MODULE_FILTERINPUT_CUSTOMPOST_IDS => 'customPostIDs',
            self::MODULE_FILTERINPUT_CUSTOMPOST_ID => 'customPostID',
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => 'excludeCustomPostIDs',
        );
        return $names[$module[1]] ?? parent::getName($module);
    }

    public function getSchemaFilterInputType(array $module): string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_IDS => SchemaDefinition::TYPE_ID,
            self::MODULE_FILTERINPUT_CUSTOMPOST_ID => SchemaDefinition::TYPE_ID,
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => SchemaDefinition::TYPE_ID,
            default => $this->getDefaultSchemaFilterInputType(),
        };
    }

    public function getSchemaFilterInputIsArrayType(array $module): bool
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_IDS,
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS
                => true,
            default
                => false,
        };
    }

    public function getSchemaFilterInputIsNonNullableItemsInArrayType(array $module): bool
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_IDS,
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS
            => true,
            default
                => false,
        };
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_IDS => $this->translationAPI->__('Limit results to elements with the given custom post IDs', 'comments'),
            self::MODULE_FILTERINPUT_CUSTOMPOST_ID => $this->translationAPI->__('Limit results to elements with the given custom post ID', 'comments'),
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => $this->translationAPI->__('Exclude elements with the given custom post IDs', 'comments'),
            default => null,
        };
    }
}
