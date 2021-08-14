<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\ModuleProcessors\FormInputs;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\AbstractFormInputModuleProcessor;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\FilterInputProcessors\FilterInputProcessor;

class FilterInputModuleProcessor extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_AUTHOR_IDS = 'filterinput-author-ids';
    public const MODULE_FILTERINPUT_AUTHOR_SLUG = 'filterinput-author-slug';
    public const MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS = 'filterinput-exclude-author-ids';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_AUTHOR_IDS],
            [self::class, self::MODULE_FILTERINPUT_AUTHOR_SLUG],
            [self::class, self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_AUTHOR_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_AUTHOR_IDS],
            self::MODULE_FILTERINPUT_AUTHOR_SLUG => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_AUTHOR_SLUG],
            self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EXCLUDE_AUTHOR_IDS],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getName(array $module): string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_AUTHOR_IDS => 'authorIDs',
            self::MODULE_FILTERINPUT_AUTHOR_SLUG => 'authorSlug',
            self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS => 'excludeAuthorIDs',
            default => parent::getName($module),
        };
    }

    public function getSchemaFilterInputType(array $module): string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_AUTHOR_IDS => SchemaDefinition::TYPE_ID,
            self::MODULE_FILTERINPUT_AUTHOR_SLUG => SchemaDefinition::TYPE_STRING,
            self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS => SchemaDefinition::TYPE_ID,
            default => $this->getDefaultSchemaFilterInputType(),
        };
    }

    public function getSchemaFilterInputIsArrayType(array $module): bool
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_AUTHOR_IDS => true,
            self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS => true,
            default => false,
        };
    }

    public function getSchemaFilterInputIsNonNullableItemsInArrayType(array $module): bool
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_AUTHOR_IDS => true,
            self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS => true,
            default => false,
        };
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $descriptions = [
            self::MODULE_FILTERINPUT_AUTHOR_IDS => $this->translationAPI->__('Search custom posts from the authors with given IDs', 'pop-users'),
            self::MODULE_FILTERINPUT_AUTHOR_SLUG => $this->translationAPI->__('Search custom posts from the authors with given slug', 'pop-users'),
            self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS => $this->translationAPI->__('Search custom posts excluding the ones from authors with given IDs', 'pop-users'),
        ];
        return $descriptions[$module[1]] ?? null;
    }
}
