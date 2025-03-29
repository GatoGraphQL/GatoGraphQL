<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\GenericCategoryDeleteMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCategoryDeleteMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver $genericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver(): GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver */
            $genericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver = $genericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericCategoryDeleteMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver();
    }
}
