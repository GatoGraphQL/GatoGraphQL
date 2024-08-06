<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\ObjectType\GenericCategoryUpdateMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCategoryUpdateMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver $genericCategoryUpdateMutationErrorPayloadUnionTypeResolver = null;

    final public function setGenericCategoryUpdateMutationErrorPayloadUnionTypeResolver(GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver $genericCategoryUpdateMutationErrorPayloadUnionTypeResolver): void
    {
        $this->genericCategoryUpdateMutationErrorPayloadUnionTypeResolver = $genericCategoryUpdateMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getGenericCategoryUpdateMutationErrorPayloadUnionTypeResolver(): GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCategoryUpdateMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver */
            $genericCategoryUpdateMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCategoryUpdateMutationErrorPayloadUnionTypeResolver = $genericCategoryUpdateMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCategoryUpdateMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericCategoryUpdateMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericCategoryUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
