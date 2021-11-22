<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPSchema\CustomPosts\ComponentConfiguration;
use PoPSchema\CustomPosts\FilterInputProcessors\FilterInputProcessor;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor as SchemaCommonsFilterInputProcessor;
use PoPSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;

abstract class AbstractCustomPostsFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    private ?CustomPostDateQueryInputObjectTypeResolver $customPostDateQueryInputObjectTypeResolver = null;
    private ?CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver = null;

    final public function setCustomPostDateQueryInputObjectTypeResolver(CustomPostDateQueryInputObjectTypeResolver $customPostDateQueryInputObjectTypeResolver): void
    {
        $this->customPostDateQueryInputObjectTypeResolver = $customPostDateQueryInputObjectTypeResolver;
    }
    final protected function getCustomPostDateQueryInputObjectTypeResolver(): CustomPostDateQueryInputObjectTypeResolver
    {
        return $this->customPostDateQueryInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostDateQueryInputObjectTypeResolver::class);
    }
    final public function setCustomPostStatusEnumTypeResolver(CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver): void
    {
        $this->customPostStatusEnumTypeResolver = $customPostStatusEnumTypeResolver;
    }
    final protected function getCustomPostStatusEnumTypeResolver(): CustomPostStatusEnumTypeResolver
    {
        return $this->customPostStatusEnumTypeResolver ??= $this->instanceManager->getInstance(CustomPostStatusEnumTypeResolver::class);
    }

    public function getAdminInputFieldNames(): array
    {
        $adminInputFieldNames = parent::getAdminInputFieldNames();
        if (ComponentConfiguration::treatCustomPostStatusAsAdminData()) {
            $adminInputFieldNames[] = 'status';
        }
        return $adminInputFieldNames;
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'status' => $this->getCustomPostStatusEnumTypeResolver(),
                'search' => $this->getStringScalarTypeResolver(),
                'dateQuery' => $this->getCustomPostDateQueryInputObjectTypeResolver(),
            ]
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'status' => $this->getTranslationAPI()->__('Custom Post Status', 'customposts'),
            'search' => $this->getTranslationAPI()->__('Search for elements containing the given string', 'customposts'),
            'dateQuery' => $this->getTranslationAPI()->__('Filter elements based on date', 'customposts'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'status' => [
                Status::PUBLISHED,
            ],
            default => parent::getInputFieldDefaultValue($inputFieldName)
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'status' => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getInputFieldTypeModifiers($inputFieldName)
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?array
    {
        return match ($inputFieldName) {
            'status' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_CUSTOMPOSTSTATUS],
            'search' => [SchemaCommonsFilterInputProcessor::class, SchemaCommonsFilterInputProcessor::FILTERINPUT_SEARCH],
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
