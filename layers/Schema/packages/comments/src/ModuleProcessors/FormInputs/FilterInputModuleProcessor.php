<?php

declare(strict_types=1);

namespace PoPSchema\Comments\ModuleProcessors\FormInputs;

use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\ModuleProcessors\AbstractFormInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoPSchema\Comments\Constants\CommentStatus;
use PoPSchema\Comments\Constants\CommentTypes;
use PoPSchema\Comments\Enums\CommentStatusEnum;
use PoPSchema\Comments\Enums\CommentTypeEnum;
use PoPSchema\Comments\FilterInputProcessors\FilterInputProcessor;

class FilterInputModuleProcessor extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_CUSTOMPOST_IDS = 'filterinput-custompost-ids';
    public const MODULE_FILTERINPUT_CUSTOMPOST_ID = 'filterinput-custompost-id';
    public const MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS = 'filterinput-exclude-custompost-ids';
    public const MODULE_FILTERINPUT_COMMENT_TYPES = 'filterinput-comment-types';
    public const MODULE_FILTERINPUT_COMMENT_STATUS = 'filterinput-comment-status';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_CUSTOMPOST_IDS],
            [self::class, self::MODULE_FILTERINPUT_CUSTOMPOST_ID],
            [self::class, self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS],
            [self::class, self::MODULE_FILTERINPUT_COMMENT_TYPES],
            [self::class, self::MODULE_FILTERINPUT_COMMENT_STATUS],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_CUSTOMPOST_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_CUSTOMPOST_IDS],
            self::MODULE_FILTERINPUT_CUSTOMPOST_ID => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_CUSTOMPOST_ID],
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS],
            self::MODULE_FILTERINPUT_COMMENT_TYPES => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_COMMENT_TYPES],
            self::MODULE_FILTERINPUT_COMMENT_STATUS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_COMMENT_STATUS],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CUSTOMPOST_IDS:
            case self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS:
            case self::MODULE_FILTERINPUT_COMMENT_TYPES:
            case self::MODULE_FILTERINPUT_COMMENT_STATUS:
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
            self::MODULE_FILTERINPUT_COMMENT_TYPES => 'types',
            self::MODULE_FILTERINPUT_COMMENT_STATUS => 'status',
        );
        return $names[$module[1]] ?? parent::getName($module);
    }

    public function getSchemaFilterInputType(array $module): string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_IDS => SchemaDefinition::TYPE_ID,
            self::MODULE_FILTERINPUT_CUSTOMPOST_ID => SchemaDefinition::TYPE_ID,
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => SchemaDefinition::TYPE_ID,
            self::MODULE_FILTERINPUT_COMMENT_TYPES => SchemaDefinition::TYPE_ENUM,
            self::MODULE_FILTERINPUT_COMMENT_STATUS => SchemaDefinition::TYPE_ENUM,
            default => $this->getDefaultSchemaFilterInputType(),
        };
    }

    public function getSchemaFilterInputIsArrayType(array $module): bool
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_IDS,
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS,
            self::MODULE_FILTERINPUT_COMMENT_TYPES,
            self::MODULE_FILTERINPUT_COMMENT_STATUS
                => true,
            default
                => false,
        };
    }

    public function getSchemaFilterInputIsNonNullableItemsInArrayType(array $module): bool
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_IDS,
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS,
            self::MODULE_FILTERINPUT_COMMENT_TYPES,
            self::MODULE_FILTERINPUT_COMMENT_STATUS
                => true,
            default
                => false,
        };
    }

    public function getSchemaFilterInputDefaultValue(array $module): mixed
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_COMMENT_TYPES => [
                CommentTypes::COMMENT,
            ],
            self::MODULE_FILTERINPUT_COMMENT_STATUS => [
                CommentStatus::APPROVE,
            ],
            default => null,
        };
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_IDS => $this->translationAPI->__('Limit results to elements with the given custom post IDs', 'comments'),
            self::MODULE_FILTERINPUT_CUSTOMPOST_ID => $this->translationAPI->__('Limit results to elements with the given custom post ID', 'comments'),
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => $this->translationAPI->__('Exclude elements with the given custom post IDs', 'comments'),
            self::MODULE_FILTERINPUT_COMMENT_TYPES => $this->translationAPI->__('Types of comment', 'comments'),
            self::MODULE_FILTERINPUT_COMMENT_STATUS => $this->translationAPI->__('Status of the comment', 'comments'),
            default => null,
        };
    }

    public function addSchemaDefinitionForFilter(array &$schemaDefinition, array $module): void
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_COMMENT_TYPES:
                /**
                 * @var CommentTypeEnum
                 */
                $commentTypeEnum = $this->instanceManager->getInstance(CommentTypeEnum::class);
                $schemaDefinition[SchemaDefinition::ARGNAME_ENUM_NAME] = $commentTypeEnum->getTypeName();
                $schemaDefinition[SchemaDefinition::ARGNAME_ENUM_VALUES] = SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                    $commentTypeEnum
                );
                break;
            case self::MODULE_FILTERINPUT_COMMENT_STATUS:
                /**
                 * @var CommentStatusEnum
                 */
                $commentStatusEnum = $this->instanceManager->getInstance(CommentStatusEnum::class);
                $schemaDefinition[SchemaDefinition::ARGNAME_ENUM_NAME] = $commentStatusEnum->getTypeName();
                $schemaDefinition[SchemaDefinition::ARGNAME_ENUM_VALUES] = SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                    $commentStatusEnum
                );
                break;
        }
    }
}
