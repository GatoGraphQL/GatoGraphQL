<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ComponentProcessors\FormInputs;

use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPCMSSchema\Comments\Constants\CommentStatus;
use PoPCMSSchema\Comments\Constants\CommentTypes;
use PoPCMSSchema\Comments\FilterInputProcessors\CommentStatusFilterInputProcessor;
use PoPCMSSchema\Comments\FilterInputProcessors\CommentTypesFilterInputProcessor;
use PoPCMSSchema\Comments\FilterInputProcessors\CustomPostIDFilterInputProcessor;
use PoPCMSSchema\Comments\FilterInputProcessors\CustomPostIDsFilterInputProcessor;
use PoPCMSSchema\Comments\FilterInputProcessors\ExcludeCustomPostIDsFilterInputProcessor;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentStatusEnumTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentTypeEnumTypeResolver;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const COMPONENT_FILTERINPUT_CUSTOMPOST_IDS = 'filterinput-custompost-ids';
    public final const COMPONENT_FILTERINPUT_CUSTOMPOST_ID = 'filterinput-custompost-id';
    public final const COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS = 'filterinput-exclude-custompost-ids';
    public final const COMPONENT_FILTERINPUT_COMMENT_TYPES = 'filterinput-comment-types';
    public final const COMPONENT_FILTERINPUT_COMMENT_STATUS = 'filterinput-comment-status';

    private ?CommentTypeEnumTypeResolver $commentTypeEnumTypeResolver = null;
    private ?CommentStatusEnumTypeResolver $commentStatusEnumTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?CommentStatusFilterInputProcessor $commentStatusFilterInputProcessor = null;
    private ?CommentTypesFilterInputProcessor $commentTypesFilterInputProcessor = null;
    private ?CustomPostIDFilterInputProcessor $customPostIDFilterInputProcessor = null;
    private ?CustomPostIDsFilterInputProcessor $customPostIDsFilterInputProcessor = null;
    private ?ExcludeCustomPostIDsFilterInputProcessor $excludeCustomPostIDsFilterInputProcessor = null;

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
    final public function setCommentStatusFilterInputProcessor(CommentStatusFilterInputProcessor $commentStatusFilterInputProcessor): void
    {
        $this->commentStatusFilterInputProcessor = $commentStatusFilterInputProcessor;
    }
    final protected function getCommentStatusFilterInputProcessor(): CommentStatusFilterInputProcessor
    {
        return $this->commentStatusFilterInputProcessor ??= $this->instanceManager->getInstance(CommentStatusFilterInputProcessor::class);
    }
    final public function setCommentTypesFilterInputProcessor(CommentTypesFilterInputProcessor $commentTypesFilterInputProcessor): void
    {
        $this->commentTypesFilterInputProcessor = $commentTypesFilterInputProcessor;
    }
    final protected function getCommentTypesFilterInputProcessor(): CommentTypesFilterInputProcessor
    {
        return $this->commentTypesFilterInputProcessor ??= $this->instanceManager->getInstance(CommentTypesFilterInputProcessor::class);
    }
    final public function setCustomPostIDFilterInputProcessor(CustomPostIDFilterInputProcessor $customPostIDFilterInputProcessor): void
    {
        $this->customPostIDFilterInputProcessor = $customPostIDFilterInputProcessor;
    }
    final protected function getCustomPostIDFilterInputProcessor(): CustomPostIDFilterInputProcessor
    {
        return $this->customPostIDFilterInputProcessor ??= $this->instanceManager->getInstance(CustomPostIDFilterInputProcessor::class);
    }
    final public function setCustomPostIDsFilterInputProcessor(CustomPostIDsFilterInputProcessor $customPostIDsFilterInputProcessor): void
    {
        $this->customPostIDsFilterInputProcessor = $customPostIDsFilterInputProcessor;
    }
    final protected function getCustomPostIDsFilterInputProcessor(): CustomPostIDsFilterInputProcessor
    {
        return $this->customPostIDsFilterInputProcessor ??= $this->instanceManager->getInstance(CustomPostIDsFilterInputProcessor::class);
    }
    final public function setExcludeCustomPostIDsFilterInputProcessor(ExcludeCustomPostIDsFilterInputProcessor $excludeCustomPostIDsFilterInputProcessor): void
    {
        $this->excludeCustomPostIDsFilterInputProcessor = $excludeCustomPostIDsFilterInputProcessor;
    }
    final protected function getExcludeCustomPostIDsFilterInputProcessor(): ExcludeCustomPostIDsFilterInputProcessor
    {
        return $this->excludeCustomPostIDsFilterInputProcessor ??= $this->instanceManager->getInstance(ExcludeCustomPostIDsFilterInputProcessor::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS],
            [self::class, self::COMPONENT_FILTERINPUT_CUSTOMPOST_ID],
            [self::class, self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS],
            [self::class, self::COMPONENT_FILTERINPUT_COMMENT_TYPES],
            [self::class, self::COMPONENT_FILTERINPUT_COMMENT_STATUS],
        );
    }

    public function getFilterInput(array $component): ?FilterInputProcessorInterface
    {
        $filterInputs = [
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS => $this->getCustomPostIDsFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_ID => $this->getCustomPostIDFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => $this->getExcludeCustomPostIDsFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_COMMENT_TYPES => $this->getCommentTypesFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_COMMENT_STATUS => $this->getCommentStatusFilterInputProcessor(),
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS:
            case self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS:
            case self::COMPONENT_FILTERINPUT_COMMENT_TYPES:
            case self::COMPONENT_FILTERINPUT_COMMENT_STATUS:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($component);
    }

    public function getName(array $component): string
    {
        // Add a nice name, so that the URL params when filtering make sense
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS => 'customPostIDs',
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_ID => 'customPostID',
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => 'excludeCustomPostIDs',
            self::COMPONENT_FILTERINPUT_COMMENT_TYPES => 'types',
            self::COMPONENT_FILTERINPUT_COMMENT_STATUS => 'status',
            default => parent::getName($component),
        };
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS => $this->getIDScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_ID => $this->getIDScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => $this->getIDScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_COMMENT_TYPES => $this->getCommentTypeEnumTypeResolver(),
            self::COMPONENT_FILTERINPUT_COMMENT_STATUS => $this->getCommentStatusEnumTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS,
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS,
            self::COMPONENT_FILTERINPUT_COMMENT_TYPES,
            self::COMPONENT_FILTERINPUT_COMMENT_STATUS
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDefaultValue(array $component): mixed
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_COMMENT_TYPES => [
                CommentTypes::COMMENT,
            ],
            self::COMPONENT_FILTERINPUT_COMMENT_STATUS => [
                CommentStatus::APPROVE,
            ],
            default => null,
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS => $this->__('Limit results to elements with the given custom post IDs', 'comments'),
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_ID => $this->__('Limit results to elements with the given custom post ID', 'comments'),
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => $this->__('Exclude elements with the given custom post IDs', 'comments'),
            self::COMPONENT_FILTERINPUT_COMMENT_TYPES => $this->__('Types of comment', 'comments'),
            self::COMPONENT_FILTERINPUT_COMMENT_STATUS => $this->__('Status of the comment', 'comments'),
            default => null,
        };
    }
}
