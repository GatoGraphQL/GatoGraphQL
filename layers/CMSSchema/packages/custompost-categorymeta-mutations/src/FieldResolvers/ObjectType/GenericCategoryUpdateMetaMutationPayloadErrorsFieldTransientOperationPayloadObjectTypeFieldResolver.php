<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\GenericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\GenericCategoryUpdateMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCategoryUpdateMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver $genericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver(): GenericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $genericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver = $genericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericCategoryUpdateMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
