<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoPCMSSchema\Comments\Constants\CommentStatus;
use PoPCMSSchema\Comments\Constants\CommentTypes;
use PoPCMSSchema\Comments\FilterInputProcessors\CommentStatusFilterInputProcessor;
use PoPCMSSchema\Comments\FilterInputProcessors\CommentTypesFilterInputProcessor;
use PoPCMSSchema\Comments\FilterInputProcessors\CustomPostIDFilterInputProcessor;
use PoPCMSSchema\Comments\FilterInputProcessors\CustomPostIDsFilterInputProcessor;
use PoPCMSSchema\Comments\FilterInputProcessors\ExcludeCustomPostIDsFilterInputProcessor;
use PoPCMSSchema\Comments\Module;
use PoPCMSSchema\Comments\ModuleConfiguration;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentStatusEnumTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentTypeEnumTypeResolver;
use PoPCMSSchema\CustomPosts\FilterInputProcessors\UnionCustomPostTypesFilterInputProcessor;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\ExcludeParentIDsFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\ParentIDFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\ParentIDsFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\SearchFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\DateQueryInputObjectTypeResolver;

abstract class AbstractCommentsFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    private ?DateQueryInputObjectTypeResolver $dateQueryInputObjectTypeResolver = null;
    private ?CommentStatusEnumTypeResolver $commentStatusEnumTypeResolver = null;
    private ?CommentTypeEnumTypeResolver $commentTypeEnumTypeResolver = null;
    private ?CustomPostEnumTypeResolver $customPostEnumTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?CommentStatusFilterInputProcessor $commentStatusFilterInputProcessor = null;
    private ?CommentTypesFilterInputProcessor $commentTypesFilterInputProcessor = null;
    private ?CustomPostIDFilterInputProcessor $customPostIDFilterInputProcessor = null;
    private ?CustomPostIDsFilterInputProcessor $customPostIDsFilterInputProcessor = null;
    private ?ExcludeCustomPostIDsFilterInputProcessor $excludeCustomPostIDsFilterInputProcessor = null;
    private ?UnionCustomPostTypesFilterInputProcessor $unionCustomPostTypesFilterInputProcessor = null;
    private ?SearchFilterInputProcessor $searchFilterInputProcessor = null;
    private ?ParentIDFilterInputProcessor $parentIDFilterInputProcessor = null;
    private ?ParentIDsFilterInputProcessor $parentIDsFilterInputProcessor = null;
    private ?ExcludeParentIDsFilterInputProcessor $excludeParentIDsFilterInputProcessor = null;

    final public function setDateQueryInputObjectTypeResolver(DateQueryInputObjectTypeResolver $dateQueryInputObjectTypeResolver): void
    {
        $this->dateQueryInputObjectTypeResolver = $dateQueryInputObjectTypeResolver;
    }
    final protected function getDateQueryInputObjectTypeResolver(): DateQueryInputObjectTypeResolver
    {
        return $this->dateQueryInputObjectTypeResolver ??= $this->instanceManager->getInstance(DateQueryInputObjectTypeResolver::class);
    }
    final public function setCommentStatusEnumTypeResolver(CommentStatusEnumTypeResolver $commentStatusEnumTypeResolver): void
    {
        $this->commentStatusEnumTypeResolver = $commentStatusEnumTypeResolver;
    }
    final protected function getCommentStatusEnumTypeResolver(): CommentStatusEnumTypeResolver
    {
        return $this->commentStatusEnumTypeResolver ??= $this->instanceManager->getInstance(CommentStatusEnumTypeResolver::class);
    }
    final public function setCommentTypeEnumTypeResolver(CommentTypeEnumTypeResolver $commentTypeEnumTypeResolver): void
    {
        $this->commentTypeEnumTypeResolver = $commentTypeEnumTypeResolver;
    }
    final protected function getCommentTypeEnumTypeResolver(): CommentTypeEnumTypeResolver
    {
        return $this->commentTypeEnumTypeResolver ??= $this->instanceManager->getInstance(CommentTypeEnumTypeResolver::class);
    }
    final public function setCustomPostEnumTypeResolver(CustomPostEnumTypeResolver $customPostEnumTypeResolver): void
    {
        $this->customPostEnumTypeResolver = $customPostEnumTypeResolver;
    }
    final protected function getCustomPostEnumTypeResolver(): CustomPostEnumTypeResolver
    {
        return $this->customPostEnumTypeResolver ??= $this->instanceManager->getInstance(CustomPostEnumTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
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
    final public function setUnionCustomPostTypesFilterInputProcessor(UnionCustomPostTypesFilterInputProcessor $unionCustomPostTypesFilterInputProcessor): void
    {
        $this->unionCustomPostTypesFilterInputProcessor = $unionCustomPostTypesFilterInputProcessor;
    }
    final protected function getUnionCustomPostTypesFilterInputProcessor(): UnionCustomPostTypesFilterInputProcessor
    {
        return $this->unionCustomPostTypesFilterInputProcessor ??= $this->instanceManager->getInstance(UnionCustomPostTypesFilterInputProcessor::class);
    }
    final public function setExcludeParentIDsFilterInputProcessor(ExcludeParentIDsFilterInputProcessor $excludeParentIDsFilterInputProcessor): void
    {
        $this->excludeParentIDsFilterInputProcessor = $excludeParentIDsFilterInputProcessor;
    }
    final protected function getExcludeParentIDsFilterInputProcessor(): ExcludeParentIDsFilterInputProcessor
    {
        return $this->excludeParentIDsFilterInputProcessor ??= $this->instanceManager->getInstance(ExcludeParentIDsFilterInputProcessor::class);
    }
    final public function setSearchFilterInputProcessor(SearchFilterInputProcessor $searchFilterInputProcessor): void
    {
        $this->searchFilterInputProcessor = $searchFilterInputProcessor;
    }
    final protected function getSearchFilterInputProcessor(): SearchFilterInputProcessor
    {
        return $this->searchFilterInputProcessor ??= $this->instanceManager->getInstance(SearchFilterInputProcessor::class);
    }
    final public function setParentIDFilterInputProcessor(ParentIDFilterInputProcessor $parentIDFilterInputProcessor): void
    {
        $this->parentIDFilterInputProcessor = $parentIDFilterInputProcessor;
    }
    final protected function getParentIDFilterInputProcessor(): ParentIDFilterInputProcessor
    {
        return $this->parentIDFilterInputProcessor ??= $this->instanceManager->getInstance(ParentIDFilterInputProcessor::class);
    }
    final public function setParentIDsFilterInputProcessor(ParentIDsFilterInputProcessor $parentIDsFilterInputProcessor): void
    {
        $this->parentIDsFilterInputProcessor = $parentIDsFilterInputProcessor;
    }
    final protected function getParentIDsFilterInputProcessor(): ParentIDsFilterInputProcessor
    {
        return $this->parentIDsFilterInputProcessor ??= $this->instanceManager->getInstance(ParentIDsFilterInputProcessor::class);
    }

    public function getAdminInputFieldNames(): array
    {
        $adminInputFieldNames = parent::getAdminInputFieldNames();
        if ($this->treatCommentStatusAsAdminData()) {
            $adminInputFieldNames[] = 'status';
        }
        return $adminInputFieldNames;
    }

    protected function treatCommentStatusAsAdminData(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->treatCommentStatusAsAdminData();
    }

    abstract protected function addParentInputFields(): bool;
    abstract protected function addCustomPostInputFields(): bool;

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
                'customPostTypes' => $this->getCustomPostEnumTypeResolver(),
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
            'customPostTypes' => $this->getCustomPostEnumTypeResolver()->getConsolidatedEnumValues(),
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
            'customPostTypes'
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName)
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputProcessorInterface
    {
        return match ($inputFieldName) {
            'status' => $this->getCommentStatusFilterInputProcessor(),
            'search' => $this->getSearchFilterInputProcessor(),
            'types' => $this->getCommentTypesFilterInputProcessor(),
            'parentID' => $this->getParentIDFilterInputProcessor(),
            'parentIDs' => $this->getParentIDsFilterInputProcessor(),
            'excludeParentIDs' => $this->getExcludeParentIDsFilterInputProcessor(),
            'customPostID' => $this->getCustomPostIDFilterInputProcessor(),
            'customPostIDs' => $this->getCustomPostIDsFilterInputProcessor(),
            'excludeCustomPostIDs' => $this->getExcludeCustomPostIDsFilterInputProcessor(),
            'customPostTypes' => $this->getUnionCustomPostTypesFilterInputProcessor(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
