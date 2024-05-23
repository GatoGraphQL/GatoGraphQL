<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\TypeResolvers\InputObjectType;

use PoPCMSSchema\Comments\Constants\CommentStatus;
use PoPCMSSchema\Comments\Constants\CommentTypes;
use PoPCMSSchema\Comments\FilterInputs\CommentStatusFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CommentTypesFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CustomPostIDFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CustomPostIDsFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CustomPostStatusFilterInput;
use PoPCMSSchema\Comments\FilterInputs\ExcludeCustomPostIDsFilterInput;
use PoPCMSSchema\Comments\Module;
use PoPCMSSchema\Comments\ModuleConfiguration;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentStatusEnumTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentTypeEnumTypeResolver;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoPCMSSchema\CustomPosts\ModuleConfiguration as CustomPostsModuleConfiguration;
use PoPCMSSchema\CustomPosts\FilterInputs\UnionCustomPostTypesFilterInput;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumStringScalarTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputs\ExcludeParentIDsFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDsFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\DateQueryInputObjectTypeResolver;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;

abstract class AbstractCommentsFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    private ?DateQueryInputObjectTypeResolver $dateQueryInputObjectTypeResolver = null;
    private ?CommentStatusEnumTypeResolver $commentStatusEnumTypeResolver = null;
    private ?FilterCustomPostStatusEnumTypeResolver $filterCustomPostStatusEnumTypeResolver = null;
    private ?CommentTypeEnumTypeResolver $commentTypeEnumTypeResolver = null;
    private ?CustomPostEnumStringScalarTypeResolver $customPostEnumStringScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?CommentStatusFilterInput $commentStatusFilterInput = null;
    private ?CommentTypesFilterInput $commentTypesFilterInput = null;
    private ?CustomPostIDFilterInput $customPostIDFilterInput = null;
    private ?CustomPostIDsFilterInput $customPostIDsFilterInput = null;
    private ?CustomPostStatusFilterInput $customPostStatusFilterInput = null;
    private ?ExcludeCustomPostIDsFilterInput $excludeCustomPostIDsFilterInput = null;
    private ?UnionCustomPostTypesFilterInput $unionCustomPostTypesFilterInput = null;
    private ?SearchFilterInput $searchFilterInput = null;
    private ?ParentIDFilterInput $parentIDFilterInput = null;
    private ?ParentIDsFilterInput $parentIDsFilterInput = null;
    private ?ExcludeParentIDsFilterInput $excludeParentIDsFilterInput = null;

    final public function setDateQueryInputObjectTypeResolver(DateQueryInputObjectTypeResolver $dateQueryInputObjectTypeResolver): void
    {
        $this->dateQueryInputObjectTypeResolver = $dateQueryInputObjectTypeResolver;
    }
    final protected function getDateQueryInputObjectTypeResolver(): DateQueryInputObjectTypeResolver
    {
        if ($this->dateQueryInputObjectTypeResolver === null) {
            /** @var DateQueryInputObjectTypeResolver */
            $dateQueryInputObjectTypeResolver = $this->instanceManager->getInstance(DateQueryInputObjectTypeResolver::class);
            $this->dateQueryInputObjectTypeResolver = $dateQueryInputObjectTypeResolver;
        }
        return $this->dateQueryInputObjectTypeResolver;
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
    final public function setCustomPostEnumStringScalarTypeResolver(CustomPostEnumStringScalarTypeResolver $customPostEnumStringScalarTypeResolver): void
    {
        $this->customPostEnumStringScalarTypeResolver = $customPostEnumStringScalarTypeResolver;
    }
    final protected function getCustomPostEnumStringScalarTypeResolver(): CustomPostEnumStringScalarTypeResolver
    {
        if ($this->customPostEnumStringScalarTypeResolver === null) {
            /** @var CustomPostEnumStringScalarTypeResolver */
            $customPostEnumStringScalarTypeResolver = $this->instanceManager->getInstance(CustomPostEnumStringScalarTypeResolver::class);
            $this->customPostEnumStringScalarTypeResolver = $customPostEnumStringScalarTypeResolver;
        }
        return $this->customPostEnumStringScalarTypeResolver;
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
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
    final public function setUnionCustomPostTypesFilterInput(UnionCustomPostTypesFilterInput $unionCustomPostTypesFilterInput): void
    {
        $this->unionCustomPostTypesFilterInput = $unionCustomPostTypesFilterInput;
    }
    final protected function getUnionCustomPostTypesFilterInput(): UnionCustomPostTypesFilterInput
    {
        if ($this->unionCustomPostTypesFilterInput === null) {
            /** @var UnionCustomPostTypesFilterInput */
            $unionCustomPostTypesFilterInput = $this->instanceManager->getInstance(UnionCustomPostTypesFilterInput::class);
            $this->unionCustomPostTypesFilterInput = $unionCustomPostTypesFilterInput;
        }
        return $this->unionCustomPostTypesFilterInput;
    }
    final public function setExcludeParentIDsFilterInput(ExcludeParentIDsFilterInput $excludeParentIDsFilterInput): void
    {
        $this->excludeParentIDsFilterInput = $excludeParentIDsFilterInput;
    }
    final protected function getExcludeParentIDsFilterInput(): ExcludeParentIDsFilterInput
    {
        if ($this->excludeParentIDsFilterInput === null) {
            /** @var ExcludeParentIDsFilterInput */
            $excludeParentIDsFilterInput = $this->instanceManager->getInstance(ExcludeParentIDsFilterInput::class);
            $this->excludeParentIDsFilterInput = $excludeParentIDsFilterInput;
        }
        return $this->excludeParentIDsFilterInput;
    }
    final public function setSearchFilterInput(SearchFilterInput $searchFilterInput): void
    {
        $this->searchFilterInput = $searchFilterInput;
    }
    final protected function getSearchFilterInput(): SearchFilterInput
    {
        if ($this->searchFilterInput === null) {
            /** @var SearchFilterInput */
            $searchFilterInput = $this->instanceManager->getInstance(SearchFilterInput::class);
            $this->searchFilterInput = $searchFilterInput;
        }
        return $this->searchFilterInput;
    }
    final public function setParentIDFilterInput(ParentIDFilterInput $parentIDFilterInput): void
    {
        $this->parentIDFilterInput = $parentIDFilterInput;
    }
    final protected function getParentIDFilterInput(): ParentIDFilterInput
    {
        if ($this->parentIDFilterInput === null) {
            /** @var ParentIDFilterInput */
            $parentIDFilterInput = $this->instanceManager->getInstance(ParentIDFilterInput::class);
            $this->parentIDFilterInput = $parentIDFilterInput;
        }
        return $this->parentIDFilterInput;
    }
    final public function setParentIDsFilterInput(ParentIDsFilterInput $parentIDsFilterInput): void
    {
        $this->parentIDsFilterInput = $parentIDsFilterInput;
    }
    final protected function getParentIDsFilterInput(): ParentIDsFilterInput
    {
        if ($this->parentIDsFilterInput === null) {
            /** @var ParentIDsFilterInput */
            $parentIDsFilterInput = $this->instanceManager->getInstance(ParentIDsFilterInput::class);
            $this->parentIDsFilterInput = $parentIDsFilterInput;
        }
        return $this->parentIDsFilterInput;
    }

    /**
     * @return string[]
     */
    public function getSensitiveInputFieldNames(): array
    {
        $sensitiveInputFieldNames = parent::getSensitiveInputFieldNames();
        if ($this->treatCommentStatusAsSensitiveData()) {
            $sensitiveInputFieldNames[] = 'status';
        }
        if ($this->treatCustomPostStatusAsSensitiveData()) {
            $sensitiveInputFieldNames[] = 'customPostStatus';
        }
        return $sensitiveInputFieldNames;
    }

    protected function treatCommentStatusAsSensitiveData(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->treatCommentStatusAsSensitiveData();
    }

    protected function treatCustomPostStatusAsSensitiveData(): bool
    {
        /** @var CustomPostsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostsModule::class)->getConfiguration();
        return $moduleConfiguration->treatCustomPostStatusAsSensitiveData();
    }

    abstract protected function addParentInputFields(): bool;
    abstract protected function addCustomPostInputFields(): bool;

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'status' => $this->getCommentStatusEnumTypeResolver(),
                'search' => $this->getStringScalarTypeResolver(),
                'dateQuery' => $this->getDateQueryInputObjectTypeResolver(),
                'types' => $this->getCommentTypeEnumTypeResolver(),
            ],
            $this->addParentInputFields() ? [
                'parentID' => $this->getIDScalarTypeResolver(),
                'parentIDs' => $this->getIDScalarTypeResolver(),
                'excludeParentIDs' => $this->getIDScalarTypeResolver(),
            ] : [],
            $this->addCustomPostInputFields() ? [
                'customPostID' => $this->getIDScalarTypeResolver(),
                'customPostIDs' => $this->getIDScalarTypeResolver(),
                'excludeCustomPostIDs' => $this->getIDScalarTypeResolver(),
                'customPostStatus' => $this->getFilterCustomPostStatusEnumTypeResolver(),
                'customPostTypes' => $this->getCustomPostEnumStringScalarTypeResolver(),
            ] : []
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'status' => $this->__('Comment status', 'comments'),
            'search' => $this->__('Search for comments containing the given string', 'comments'),
            'dateQuery' => $this->__('Filter comments based on date', 'comments'),
            'types' => $this->__('Filter comments based on type', 'comments'),
            'parentID' => $this->__('Filter comments with the given parent IDs. \'0\' means \'no parent\'', 'comments'),
            'parentIDs' => $this->__('Filter comments with the given parent ID. \'0\' means \'no parent\'', 'comments'),
            'excludeParentIDs' => $this->__('Exclude comments with the given parent IDs', 'comments'),
            'customPostID' => $this->__('Filter comments added to the given custom post', 'comments'),
            'customPostIDs' => $this->__('Filter comments added to the given custom posts', 'comments'),
            'excludeCustomPostIDs' => $this->__('Exclude comments added to the given custom posts', 'comments'),
            'customPostStatus' => $this->__('Filter comments added to the custom posts with given status', 'comments'),
            'customPostTypes' => $this->__('Filter comments added to custom posts of given types', 'comments'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'status' => [
                CommentStatus::APPROVE,
            ],
            'types' => [
                CommentTypes::COMMENT,
            ],
            'customPostStatus' => [
                CustomPostStatus::PUBLISH,
            ],
            'customPostTypes' => $this->getCustomPostEnumStringScalarTypeResolver()->getConsolidatedPossibleValues(),
            default => parent::getInputFieldDefaultValue($inputFieldName)
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'status',
            'types',
            'parentIDs',
            'excludeParentIDs',
            'customPostIDs',
            'excludeCustomPostIDs',
            'customPostStatus',
            'customPostTypes'
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName)
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return match ($inputFieldName) {
            'status' => $this->getCommentStatusFilterInput(),
            'search' => $this->getSearchFilterInput(),
            'types' => $this->getCommentTypesFilterInput(),
            'parentID' => $this->getParentIDFilterInput(),
            'parentIDs' => $this->getParentIDsFilterInput(),
            'excludeParentIDs' => $this->getExcludeParentIDsFilterInput(),
            'customPostID' => $this->getCustomPostIDFilterInput(),
            'customPostIDs' => $this->getCustomPostIDsFilterInput(),
            'excludeCustomPostIDs' => $this->getExcludeCustomPostIDsFilterInput(),
            'customPostStatus' => $this->getCustomPostStatusFilterInput(),
            'customPostTypes' => $this->getUnionCustomPostTypesFilterInput(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
