<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ComponentProcessors\FormInputs;

use PoPCMSSchema\Comments\Constants\CommentStatus;
use PoPCMSSchema\Comments\Constants\CommentTypes;
use PoPCMSSchema\Comments\FilterInputs\CommentStatusFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CommentTypesFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CustomPostIDFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CustomPostIDsFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CustomPostStatusFilterInput;
use PoPCMSSchema\Comments\FilterInputs\ExcludeCustomPostIDsFilterInput;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentStatusEnumTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentTypeEnumTypeResolver;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const COMPONENT_FILTERINPUT_CUSTOMPOST_IDS = 'filterinput-custompost-ids';
    public final const COMPONENT_FILTERINPUT_CUSTOMPOST_ID = 'filterinput-custompost-id';
    public final const COMPONENT_FILTERINPUT_CUSTOMPOST_STATUS = 'filterinput-custompost-status';
    public final const COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS = 'filterinput-exclude-custompost-ids';
    public final const COMPONENT_FILTERINPUT_COMMENT_TYPES = 'filterinput-comment-types';
    public final const COMPONENT_FILTERINPUT_COMMENT_STATUS = 'filterinput-comment-status';

    private ?CommentTypeEnumTypeResolver $commentTypeEnumTypeResolver = null;
    private ?CommentStatusEnumTypeResolver $commentStatusEnumTypeResolver = null;
    private ?FilterCustomPostStatusEnumTypeResolver $filterCustomPostStatusEnumTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?CommentStatusFilterInput $commentStatusFilterInput = null;
    private ?CommentTypesFilterInput $commentTypesFilterInput = null;
    private ?CustomPostIDFilterInput $customPostIDFilterInput = null;
    private ?CustomPostIDsFilterInput $customPostIDsFilterInput = null;
    private ?CustomPostStatusFilterInput $customPostStatusFilterInput = null;
    private ?ExcludeCustomPostIDsFilterInput $excludeCustomPostIDsFilterInput = null;

    final public function setCommentTypeEnumTypeResolver(CommentTypeEnumTypeResolver $commentTypeEnumTypeResolver): void
    {
        $this->commentTypeEnumTypeResolver = $commentTypeEnumTypeResolver;
    }
    final protected function getCommentTypeEnumTypeResolver(): CommentTypeEnumTypeResolver
    {
        if ($this->commentTypeEnumTypeResolver === null) {
            /** @var CommentTypeEnumTypeResolver */
            $commentTypeEnumTypeResolver = $this->instanceManager->getInstance(CommentTypeEnumTypeResolver::class);
            $this->commentTypeEnumTypeResolver = $commentTypeEnumTypeResolver;
        }
        return $this->commentTypeEnumTypeResolver;
    }
    final public function setCommentStatusEnumTypeResolver(CommentStatusEnumTypeResolver $commentStatusEnumTypeResolver): void
    {
        $this->commentStatusEnumTypeResolver = $commentStatusEnumTypeResolver;
    }
    final protected function getCommentStatusEnumTypeResolver(): CommentStatusEnumTypeResolver
    {
        if ($this->commentStatusEnumTypeResolver === null) {
            /** @var CommentStatusEnumTypeResolver */
            $commentStatusEnumTypeResolver = $this->instanceManager->getInstance(CommentStatusEnumTypeResolver::class);
            $this->commentStatusEnumTypeResolver = $commentStatusEnumTypeResolver;
        }
        return $this->commentStatusEnumTypeResolver;
    }
    final public function setFilterCustomPostStatusEnumTypeResolver(FilterCustomPostStatusEnumTypeResolver $filterCustomPostStatusEnumTypeResolver): void
    {
        $this->filterCustomPostStatusEnumTypeResolver = $filterCustomPostStatusEnumTypeResolver;
    }
    final protected function getFilterCustomPostStatusEnumTypeResolver(): FilterCustomPostStatusEnumTypeResolver
    {
        if ($this->filterCustomPostStatusEnumTypeResolver === null) {
            /** @var FilterCustomPostStatusEnumTypeResolver */
            $filterCustomPostStatusEnumTypeResolver = $this->instanceManager->getInstance(FilterCustomPostStatusEnumTypeResolver::class);
            $this->filterCustomPostStatusEnumTypeResolver = $filterCustomPostStatusEnumTypeResolver;
        }
        return $this->filterCustomPostStatusEnumTypeResolver;
    }
    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        if ($this->idScalarTypeResolver === null) {
            /** @var IDScalarTypeResolver */
            $idScalarTypeResolver = $this->instanceManager->getInstance(IDScalarTypeResolver::class);
            $this->idScalarTypeResolver = $idScalarTypeResolver;
        }
        return $this->idScalarTypeResolver;
    }
    final public function setCommentStatusFilterInput(CommentStatusFilterInput $commentStatusFilterInput): void
    {
        $this->commentStatusFilterInput = $commentStatusFilterInput;
    }
    final protected function getCommentStatusFilterInput(): CommentStatusFilterInput
    {
        if ($this->commentStatusFilterInput === null) {
            /** @var CommentStatusFilterInput */
            $commentStatusFilterInput = $this->instanceManager->getInstance(CommentStatusFilterInput::class);
            $this->commentStatusFilterInput = $commentStatusFilterInput;
        }
        return $this->commentStatusFilterInput;
    }
    final public function setCommentTypesFilterInput(CommentTypesFilterInput $commentTypesFilterInput): void
    {
        $this->commentTypesFilterInput = $commentTypesFilterInput;
    }
    final protected function getCommentTypesFilterInput(): CommentTypesFilterInput
    {
        if ($this->commentTypesFilterInput === null) {
            /** @var CommentTypesFilterInput */
            $commentTypesFilterInput = $this->instanceManager->getInstance(CommentTypesFilterInput::class);
            $this->commentTypesFilterInput = $commentTypesFilterInput;
        }
        return $this->commentTypesFilterInput;
    }
    final public function setCustomPostIDFilterInput(CustomPostIDFilterInput $customPostIDFilterInput): void
    {
        $this->customPostIDFilterInput = $customPostIDFilterInput;
    }
    final protected function getCustomPostIDFilterInput(): CustomPostIDFilterInput
    {
        if ($this->customPostIDFilterInput === null) {
            /** @var CustomPostIDFilterInput */
            $customPostIDFilterInput = $this->instanceManager->getInstance(CustomPostIDFilterInput::class);
            $this->customPostIDFilterInput = $customPostIDFilterInput;
        }
        return $this->customPostIDFilterInput;
    }
    final public function setCustomPostIDsFilterInput(CustomPostIDsFilterInput $customPostIDsFilterInput): void
    {
        $this->customPostIDsFilterInput = $customPostIDsFilterInput;
    }
    final protected function getCustomPostIDsFilterInput(): CustomPostIDsFilterInput
    {
        if ($this->customPostIDsFilterInput === null) {
            /** @var CustomPostIDsFilterInput */
            $customPostIDsFilterInput = $this->instanceManager->getInstance(CustomPostIDsFilterInput::class);
            $this->customPostIDsFilterInput = $customPostIDsFilterInput;
        }
        return $this->customPostIDsFilterInput;
    }
    final public function setCustomPostStatusFilterInput(CustomPostStatusFilterInput $customPostStatusFilterInput): void
    {
        $this->customPostStatusFilterInput = $customPostStatusFilterInput;
    }
    final protected function getCustomPostStatusFilterInput(): CustomPostStatusFilterInput
    {
        if ($this->customPostStatusFilterInput === null) {
            /** @var CustomPostStatusFilterInput */
            $customPostStatusFilterInput = $this->instanceManager->getInstance(CustomPostStatusFilterInput::class);
            $this->customPostStatusFilterInput = $customPostStatusFilterInput;
        }
        return $this->customPostStatusFilterInput;
    }
    final public function setExcludeCustomPostIDsFilterInput(ExcludeCustomPostIDsFilterInput $excludeCustomPostIDsFilterInput): void
    {
        $this->excludeCustomPostIDsFilterInput = $excludeCustomPostIDsFilterInput;
    }
    final protected function getExcludeCustomPostIDsFilterInput(): ExcludeCustomPostIDsFilterInput
    {
        if ($this->excludeCustomPostIDsFilterInput === null) {
            /** @var ExcludeCustomPostIDsFilterInput */
            $excludeCustomPostIDsFilterInput = $this->instanceManager->getInstance(ExcludeCustomPostIDsFilterInput::class);
            $this->excludeCustomPostIDsFilterInput = $excludeCustomPostIDsFilterInput;
        }
        return $this->excludeCustomPostIDsFilterInput;
    }

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS,
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_ID,
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_STATUS,
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
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_STATUS => $this->getCustomPostStatusFilterInput(),
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
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_STATUS:
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
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_STATUS => 'customPostStatus',
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
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_STATUS => $this->getFilterCustomPostStatusEnumTypeResolver(),
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
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_STATUS,
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
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_STATUS => [
                CustomPostStatus::PUBLISH,
            ],
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
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_STATUS => $this->__('Limit results to elements with the given custom post status', 'comments'),
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS => $this->__('Exclude elements with the given custom post IDs', 'comments'),
            self::COMPONENT_FILTERINPUT_COMMENT_TYPES => $this->__('Types of comment', 'comments'),
            self::COMPONENT_FILTERINPUT_COMMENT_STATUS => $this->__('Status of the comment', 'comments'),
            default => null,
        };
    }
}
