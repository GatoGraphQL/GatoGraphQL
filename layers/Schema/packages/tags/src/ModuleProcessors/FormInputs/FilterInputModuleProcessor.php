<?php

declare(strict_types=1);

namespace PoPSchema\Tags\ModuleProcessors\FormInputs;

use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\ModuleProcessors\AbstractFormInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\Tags\FilterInputProcessors\FilterInputProcessor;

class FilterInputModuleProcessor extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_TAG_SLUGS = 'filterinput-tag-slugs';
    public const MODULE_FILTERINPUT_TAG_IDS = 'filterinput-tag-ids';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_TAG_SLUGS],
            [self::class, self::MODULE_FILTERINPUT_TAG_IDS],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_TAG_SLUGS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_TAG_SLUGS],
            self::MODULE_FILTERINPUT_TAG_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_TAG_IDS],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getInputClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_TAG_SLUGS:
            case self::MODULE_FILTERINPUT_TAG_IDS:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($module);
    }

    public function getName(array $module)
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_TAG_SLUGS => 'tagSlugs',
            self::MODULE_FILTERINPUT_TAG_IDS => 'tagIDs',
            default => parent::getName($module),
        };
    }

    public function getSchemaFilterInputType(array $module): string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_TAG_SLUGS => SchemaDefinition::TYPE_STRING,
            self::MODULE_FILTERINPUT_TAG_IDS => SchemaDefinition::TYPE_ID,
            default => $this->getDefaultSchemaFilterInputType(),
        };
    }

    public function getSchemaFilterInputIsArrayType(array $module): bool
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_TAG_SLUGS,
            self::MODULE_FILTERINPUT_TAG_IDS
                => true,
            default
                => false,
        };
    }

    public function getSchemaFilterInputIsNonNullableItemsInArrayType(array $module): bool
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_TAG_SLUGS,
            self::MODULE_FILTERINPUT_TAG_IDS
                => true,
            default
                => false,
        };
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_TAG_SLUGS => $this->translationAPI->__('Limit results to elements with the given tags', 'tags'),
            self::MODULE_FILTERINPUT_TAG_IDS => $this->translationAPI->__('Limit results to elements with the given ids', 'tags'),
            default => null,
        };
    }
}
