<?php

declare(strict_types=1);

namespace PoPSchema\Comments\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPSchema\Comments\ComponentConfiguration;
use PoPSchema\Comments\Constants\CommentStatus;
use PoPSchema\Comments\Constants\CommentTypes;
use PoPSchema\Comments\FilterInputProcessors\FilterInputProcessor;
use PoPSchema\Comments\TypeResolvers\EnumType\CommentStatusEnumTypeResolver;
use PoPSchema\Comments\TypeResolvers\EnumType\CommentTypeEnumTypeResolver;
use PoPSchema\CustomPosts\FilterInputProcessors\FilterInputProcessor as CustomPostsFilterInputProcessor;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor as SchemaCommonsFilterInputProcessor;
use PoPSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\InputObjectType\DateQueryInputObjectTypeResolver;

abstract class AbstractCommentsFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    private ?DateQueryInputObjectTypeResolver $dateQueryInputObjectTypeResolver = null;
    private ?CommentStatusEnumTypeResolver $commentStatusEnumTypeResolver = null;
    private ?CommentTypeEnumTypeResolver $commentTypeEnumTypeResolver = null;

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
        return ComponentConfiguration::treatCommentStatusAsAdminData();
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
                'customPostTypes' => $this->getStringScalarTypeResolver(),
            ] : []
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'status' => $this->getTranslationAPI()->__('Comment status', 'comments'),
            'search' => $this->getTranslationAPI()->__('Search for comments containing the given string', 'comments'),
            'dateQuery' => $this->getTranslationAPI()->__('Filter comments based on date', 'comments'),
            'types' => $this->getTranslationAPI()->__('Filter comments based on type', 'comments'),
            'parentID' => $this->getTranslationAPI()->__('Filter comments with the given parent IDs. \'0\' means \'no parent\'', 'comments'),
            'parentIDs' => $this->getTranslationAPI()->__('Filter comments with the given parent ID. \'0\' means \'no parent\'', 'comments'),
            'excludeParentIDs' => $this->getTranslationAPI()->__('Exclude comments with the given parent IDs', 'comments'),
            'customPostID' => $this->getTranslationAPI()->__('Filter comments added to the given custom post', 'comments'),
            'customPostIDs' => $this->getTranslationAPI()->__('Filter comments added to the given custom posts', 'comments'),
            'excludeCustomPostIDs' => $this->getTranslationAPI()->__('Exclude comments added to the given custom posts', 'comments'),
            'customPostTypes' => $this->getTranslationAPI()->__('Filter comments added to custom posts of given types', 'comments'),
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
            'customPostTypes' => CustomPostUnionTypeHelpers::getTargetObjectTypeResolverCustomPostTypes(),
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

    public function getInputFieldFilterInput(string $inputFieldName): ?array
    {
        return match ($inputFieldName) {
            'status' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_COMMENT_STATUS],
            'search' => [SchemaCommonsFilterInputProcessor::class, SchemaCommonsFilterInputProcessor::FILTERINPUT_SEARCH],
            'types' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_COMMENT_TYPES],
            'parentID' => [SchemaCommonsFilterInputProcessor::class, SchemaCommonsFilterInputProcessor::FILTERINPUT_PARENT_ID],
            'parentIDs' => [SchemaCommonsFilterInputProcessor::class, SchemaCommonsFilterInputProcessor::FILTERINPUT_PARENT_IDS],
            'excludeParentIDs' => [SchemaCommonsFilterInputProcessor::class, SchemaCommonsFilterInputProcessor::FILTERINPUT_EXCLUDE_PARENT_IDS],
            'customPostID' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_CUSTOMPOST_ID],
            'customPostIDs' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_CUSTOMPOST_IDS],
            'excludeCustomPostIDs' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS],
            'customPostTypes' => [CustomPostsFilterInputProcessor::class, CustomPostsFilterInputProcessor::FILTERINPUT_UNIONCUSTOMPOSTTYPES],
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
