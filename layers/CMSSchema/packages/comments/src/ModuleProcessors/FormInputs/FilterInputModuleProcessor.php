<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ComponentProcessors\FormInputs;

use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPCMSSchema\Comments\Constants\CommentStatus;
use PoPCMSSchema\Comments\Constants\CommentTypes;
use PoPCMSSchema\Comments\FilterInputProcessors\FilterInputProcessor;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentStatusEnumTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentTypeEnumTypeResolver;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const MODULE_FILTERINPUT_CUSTOMPOST_IDS = 'filterinput-custompost-ids';
    public final const MODULE_FILTERINPUT_CUSTOMPOST_ID = 'filterinput-custompost-id';
    public final const MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS = 'filterinput-exclude-custompost-ids';
    public final const MODULE_FILTERINPUT_COMMENT_TYPES = 'filterinput-comment-types';
    public final const MODULE_FILTERINPUT_COMMENT_STATUS = 'filterinput-comment-status';

    private ?CommentTypeEnumTypeResolver $commentTypeEnumTypeResolver = null;
    private ?CommentStatusEnumTypeResolver $commentStatusEnumTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;

    final public function setCommentTypeEnumTypeResolver(CommentTypeEnumTypeResolver $commentTypeEnumTypeResolver): void
    {
        $this->commentTypeEnumTypeResolver = $commentTypeEnumTypeResolver;
    }
    final protected function getCommentTypeEnumTypeResolver(): CommentTypeEnumTypeResolver
    {
        return $this->commentTypeEnumTypeResolver ??= $this->instanceManager->getInstance(CommentTypeEnumTypeResolver::class);
    }
    final public function setCommentStatusEnumTypeResolver(CommentStatusEnumTypeResolver $commentStatusEnumTypeResolver): void
    {
        $this->commentStatusEnumTypeResolver = $commentStatusEnumTypeResolver;
    }
    final protected function getCommentStatusEnumTypeResolver(): CommentStatusEnumTypeResolver
    {
        return $this->commentStatusEnumTypeResolver ??= $this->instanceManager->getInstance(CommentStatusEnumTypeResolver::class);
    }
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
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_IDS => 'customPostIDs',
            self::MODULE_FILTERINPUT_CUSTOMPOST_ID => 'customPostID',
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => 'excludeCustomPostIDs',
            self::MODULE_FILTERINPUT_COMMENT_TYPES => 'types',
            self::MODULE_FILTERINPUT_COMMENT_STATUS => 'status',
            default => parent::getName($module),
        };
    }

    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_IDS => $this->getIDScalarTypeResolver(),
            self::MODULE_FILTERINPUT_CUSTOMPOST_ID => $this->getIDScalarTypeResolver(),
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => $this->getIDScalarTypeResolver(),
            self::MODULE_FILTERINPUT_COMMENT_TYPES => $this->getCommentTypeEnumTypeResolver(),
            self::MODULE_FILTERINPUT_COMMENT_STATUS => $this->getCommentStatusEnumTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $module): int
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_IDS,
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS,
            self::MODULE_FILTERINPUT_COMMENT_TYPES,
            self::MODULE_FILTERINPUT_COMMENT_STATUS
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDefaultValue(array $module): mixed
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

    public function getFilterInputDescription(array $module): ?string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_IDS => $this->__('Limit results to elements with the given custom post IDs', 'comments'),
            self::MODULE_FILTERINPUT_CUSTOMPOST_ID => $this->__('Limit results to elements with the given custom post ID', 'comments'),
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => $this->__('Exclude elements with the given custom post IDs', 'comments'),
            self::MODULE_FILTERINPUT_COMMENT_TYPES => $this->__('Types of comment', 'comments'),
            self::MODULE_FILTERINPUT_COMMENT_STATUS => $this->__('Status of the comment', 'comments'),
            default => null,
        };
    }
}
