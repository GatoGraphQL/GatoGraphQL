<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumStringScalarTypeResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

abstract class AbstractCreateOrUpdateGenericCategoryInputObjectTypeResolver extends AbstractCreateOrUpdateCategoryInputObjectTypeResolver implements UpdateGenericCategoryInputObjectTypeResolverInterface, CreateGenericCategoryInputObjectTypeResolverInterface
{
    private ?CustomPostEnumStringScalarTypeResolver $customPostEnumStringScalarTypeResolver = null;

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

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                MutationInputProperties::CATEGORY_TYPE => $this->getCustomPostEnumStringScalarTypeResolver(),
            ]
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::CATEGORY_TYPE => $this->__('The category type', 'category-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            MutationInputProperties::CATEGORY_TYPE => SchemaTypeModifiers::MANDATORY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}
