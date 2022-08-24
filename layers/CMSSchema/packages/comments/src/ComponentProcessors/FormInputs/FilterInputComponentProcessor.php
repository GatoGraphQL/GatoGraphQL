<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ComponentProcessors\FormInputs;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPCMSSchema\Comments\Constants\CommentStatus;
use PoPCMSSchema\Comments\Constants\CommentTypes;
use PoPCMSSchema\Comments\FilterInputs\CommentStatusFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CommentTypesFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CustomPostIDFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CustomPostIDsFilterInput;
use PoPCMSSchema\Comments\FilterInputs\ExcludeCustomPostIDsFilterInput;
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
    private ?CommentStatusFilterInput $commentStatusFilterInput = null;
    private ?CommentTypesFilterInput $commentTypesFilterInput = null;
    private ?CustomPostIDFilterInput $customPostIDFilterInput = null;
    private ?CustomPostIDsFilterInput $customPostIDsFilterInput = null;
    private ?ExcludeCustomPostIDsFilterInput $excludeCustomPostIDsFilterInput = null;

    final public function setCommentTypeEnumTypeResolver(CommentTypeEnumTypeResolver $commentTypeEnumTypeResolver): void
    {
        $this->commentTypeEnumTypeResolver = $commentTypeEnumTypeResolver;
    }
    final protected function getCommentTypeEnumTypeResolver(): CommentTypeEnumTypeResolver
    {
        /** @var CommentTypeEnumTypeResolver */
        return $this->commentTypeEnumTypeResolver ??= $this->instanceManager->getInstance(CommentTypeEnumTypeResolver::class);
    }
    final public function setCommentStatusEnumTypeResolver(CommentStatusEnumTypeResolver $commentStatusEnumTypeResolver): void
    {
        $this->commentStatusEnumTypeResolver = $commentStatusEnumTypeResolver;
    }
    final protected function getCommentStatusEnumTypeResolver(): CommentStatusEnumTypeResolver
    {
        /** @var CommentStatusEnumTypeResolver */
        return $this->commentStatusEnumTypeResolver ??= $this->instanceManager->getInstance(CommentStatusEnumTypeResolver::class);
    }
    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        /** @var IDScalarTypeResolver */
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setCommentStatusFilterInput(CommentStatusFilterInput $commentStatusFilterInput): void
    {
        $this->commentStatusFilterInput = $commentStatusFilterInput;
    }
    final protected function getCommentStatusFilterInput(): CommentStatusFilterInput
    {
        /** @var CommentStatusFilterInput */
        return $this->commentStatusFilterInput ??= $this->instanceManager->getInstance(CommentStatusFilterInput::class);
    }
    final public function setCommentTypesFilterInput(CommentTypesFilterInput $commentTypesFilterInput): void
    {
        $this->commentTypesFilterInput = $commentTypesFilterInput;
    }
    final protected function getCommentTypesFilterInput(): CommentTypesFilterInput
    {
        /** @var CommentTypesFilterInput */
        return $this->commentTypesFilterInput ??= $this->instanceManager->getInstance(CommentTypesFilterInput::class);
    }
    final public function setCustomPostIDFilterInput(CustomPostIDFilterInput $customPostIDFilterInput): void
    {
        $this->customPostIDFilterInput = $customPostIDFilterInput;
    }
    final protected function getCustomPostIDFilterInput(): CustomPostIDFilterInput
    {
        /** @var CustomPostIDFilterInput */
        return $this->customPostIDFilterInput ??= $this->instanceManager->getInstance(CustomPostIDFilterInput::class);
    }
    final public function setCustomPostIDsFilterInput(CustomPostIDsFilterInput $customPostIDsFilterInput): void
    {
        $this->customPostIDsFilterInput = $customPostIDsFilterInput;
    }
    final protected function getCustomPostIDsFilterInput(): CustomPostIDsFilterInput
    {
        /** @var CustomPostIDsFilterInput */
        return $this->customPostIDsFilterInput ??= $this->instanceManager->getInstance(CustomPostIDsFilterInput::class);
    }
    final public function setExcludeCustomPostIDsFilterInput(ExcludeCustomPostIDsFilterInput $excludeCustomPostIDsFilterInput): void
    {
        $this->excludeCustomPostIDsFilterInput = $excludeCustomPostIDsFilterInput;
    }
    final protected function getExcludeCustomPostIDsFilterInput(): ExcludeCustomPostIDsFilterInput
    {
        /** @var ExcludeCustomPostIDsFilterInput */
        return $this->excludeCustomPostIDsFilterInput ??= $this->instanceManager->getInstance(ExcludeCustomPostIDsFilterInput::class);
    }

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS,
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_ID,
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS,
            self::COMPONENT_FILTERINPUT_COMMENT_TYPES,
            self::COMPONENT_FILTERINPUT_COMMENT_STATUS,
        );
    }

    public function getFilterInput(Component $component): ?FilterInputInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS => $this->getCustomPostIDsFilterInput(),
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_ID => $this->getCustomPostIDFilterInput(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => $this->getExcludeCustomPostIDsFilterInput(),
            self::COMPONENT_FILTERINPUT_COMMENT_TYPES => $this->getCommentTypesFilterInput(),
            self::COMPONENT_FILTERINPUT_COMMENT_STATUS => $this->getCommentStatusFilterInput(),
            default => null,
        };
    }

    public function getInputClass(Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS:
            case self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS:
            case self::COMPONENT_FILTERINPUT_COMMENT_TYPES:
            case self::COMPONENT_FILTERINPUT_COMMENT_STATUS:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($component);
    }

    public function getName(Component $component): string
    {
        // Add a nice name, so that the URL params when filtering make sense
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS => 'customPostIDs',
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_ID => 'customPostID',
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => 'excludeCustomPostIDs',
            self::COMPONENT_FILTERINPUT_COMMENT_TYPES => 'types',
            self::COMPONENT_FILTERINPUT_COMMENT_STATUS => 'status',
            default => parent::getName($component),
        };
    }

    public function getFilterInputTypeResolver(Component $component): InputTypeResolverInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS => $this->getIDScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_ID => $this->getIDScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => $this->getIDScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_COMMENT_TYPES => $this->getCommentTypeEnumTypeResolver(),
            self::COMPONENT_FILTERINPUT_COMMENT_STATUS => $this->getCommentStatusEnumTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(Component $component): int
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS,
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS,
            self::COMPONENT_FILTERINPUT_COMMENT_TYPES,
            self::COMPONENT_FILTERINPUT_COMMENT_STATUS
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDefaultValue(Component $component): mixed
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_COMMENT_TYPES => [
                CommentTypes::COMMENT,
            ],
            self::COMPONENT_FILTERINPUT_COMMENT_STATUS => [
                CommentStatus::APPROVE,
            ],
            default => null,
        };
    }

    public function getFilterInputDescription(Component $component): ?string
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS => $this->__('Limit results to elements with the given custom post IDs', 'comments'),
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_ID => $this->__('Limit results to elements with the given custom post ID', 'comments'),
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => $this->__('Exclude elements with the given custom post IDs', 'comments'),
            self::COMPONENT_FILTERINPUT_COMMENT_TYPES => $this->__('Types of comment', 'comments'),
            self::COMPONENT_FILTERINPUT_COMMENT_STATUS => $this->__('Status of the comment', 'comments'),
            default => null,
        };
    }
}
