<?php

declare(strict_types=1);

namespace PoPSchema\Pages\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor as SchemaCommonsFilterInputProcessor;

abstract class AbstractPagesFilterInputObjectTypeResolver extends AbstractCustomPostsFilterInputObjectTypeResolver
{
    abstract protected function addParentInputFields(): bool;

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            $this->addParentInputFields() ? [
                'parentID' => $this->getIDScalarTypeResolver(),
                'parentIDs' => $this->getIDScalarTypeResolver(),
                'excludeParentIDs' => $this->getIDScalarTypeResolver(),
            ] : [],
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'parentID' => $this->__('Filter pages with the given parent IDs. \'0\' means \'no parent\'', 'pages'),
            'parentIDs' => $this->__('Filter pages with the given parent ID. \'0\' means \'no parent\'', 'pages'),
            'excludeParentIDs' => $this->__('Exclude pages with the given parent IDs', 'pages'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'parentIDs',
            'excludeParentIDs'
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName)
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?array
    {
        return match ($inputFieldName) {
            'parentID' => [SchemaCommonsFilterInputProcessor::class, SchemaCommonsFilterInputProcessor::FILTERINPUT_PARENT_ID],
            'parentIDs' => [SchemaCommonsFilterInputProcessor::class, SchemaCommonsFilterInputProcessor::FILTERINPUT_PARENT_IDS],
            'excludeParentIDs' => [SchemaCommonsFilterInputProcessor::class, SchemaCommonsFilterInputProcessor::FILTERINPUT_EXCLUDE_PARENT_IDS],
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
