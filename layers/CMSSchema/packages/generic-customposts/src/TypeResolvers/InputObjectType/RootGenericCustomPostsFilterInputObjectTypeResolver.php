<?php

declare(strict_types=1);

namespace PoPCMSSchema\GenericCustomPosts\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostsFilterInputObjectTypeResolverInterface;
use PoPCMSSchema\GenericCustomPosts\FilterInputProcessors\FilterInputProcessor;
use PoPCMSSchema\GenericCustomPosts\TypeResolvers\EnumType\GenericCustomPostEnumTypeResolver;

class RootGenericCustomPostsFilterInputObjectTypeResolver extends AbstractCustomPostsFilterInputObjectTypeResolver implements CustomPostsFilterInputObjectTypeResolverInterface
{
    private ?GenericCustomPostEnumTypeResolver $customPostEnumTypeResolver = null;

    final public function setGenericCustomPostEnumTypeResolver(GenericCustomPostEnumTypeResolver $customPostEnumTypeResolver): void
    {
        $this->customPostEnumTypeResolver = $customPostEnumTypeResolver;
    }
    final protected function getGenericCustomPostEnumTypeResolver(): GenericCustomPostEnumTypeResolver
    {
        return $this->customPostEnumTypeResolver ??= $this->instanceManager->getInstance(GenericCustomPostEnumTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'RootGenericCustomPostsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter generic custom posts', 'genericcustomposts');
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'customPostTypes' => $this->getGenericCustomPostEnumTypeResolver(),
            ]
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'customPostTypes' => $this->__('Filter generic custom posts of given types', 'genericcustomposts'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'customPostTypes' => $this->getGenericCustomPostEnumTypeResolver()->getConsolidatedEnumValues(),
            default => parent::getInputFieldDefaultValue($inputFieldName)
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'customPostTypes' => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getInputFieldTypeModifiers($inputFieldName)
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputProcessorInterface
    {
        return match ($inputFieldName) {
            'customPostTypes' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_GENERICCUSTOMPOSTTYPES],
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
