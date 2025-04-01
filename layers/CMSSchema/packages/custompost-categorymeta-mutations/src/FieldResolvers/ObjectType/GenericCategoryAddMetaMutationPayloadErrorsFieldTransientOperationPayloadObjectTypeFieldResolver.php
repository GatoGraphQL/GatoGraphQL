<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\GenericCategoryAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\GenericCategoryAddMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCategoryAddMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericCategoryAddMetaMutationErrorPayloadUnionTypeResolver $genericCategoryAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCategoryAddMetaMutationErrorPayloadUnionTypeResolver(): GenericCategoryAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCategoryAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCategoryAddMetaMutationErrorPayloadUnionTypeResolver */
            $genericCategoryAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCategoryAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCategoryAddMetaMutationErrorPayloadUnionTypeResolver = $genericCategoryAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCategoryAddMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericCategoryAddMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericCategoryAddMetaMutationErrorPayloadUnionTypeResolver();
    }
}
