<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\ObjectType\GenericCustomPostSetCategoriesMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType\GenericCustomPostSetCategoriesMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCustomPostSetCategoriesMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericCustomPostSetCategoriesMutationErrorPayloadUnionTypeResolver $genericCustomPostSetCategoriesMutationErrorPayloadUnionTypeResolver = null;

    final public function setGenericCustomPostSetCategoriesMutationErrorPayloadUnionTypeResolver(GenericCustomPostSetCategoriesMutationErrorPayloadUnionTypeResolver $genericCustomPostSetCategoriesMutationErrorPayloadUnionTypeResolver): void
    {
        $this->genericCustomPostSetCategoriesMutationErrorPayloadUnionTypeResolver = $genericCustomPostSetCategoriesMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getGenericCustomPostSetCategoriesMutationErrorPayloadUnionTypeResolver(): GenericCustomPostSetCategoriesMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCustomPostSetCategoriesMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCustomPostSetCategoriesMutationErrorPayloadUnionTypeResolver */
            $genericCustomPostSetCategoriesMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCustomPostSetCategoriesMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCustomPostSetCategoriesMutationErrorPayloadUnionTypeResolver = $genericCustomPostSetCategoriesMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCustomPostSetCategoriesMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericCustomPostSetCategoriesMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericCustomPostSetCategoriesMutationErrorPayloadUnionTypeResolver();
    }
}
