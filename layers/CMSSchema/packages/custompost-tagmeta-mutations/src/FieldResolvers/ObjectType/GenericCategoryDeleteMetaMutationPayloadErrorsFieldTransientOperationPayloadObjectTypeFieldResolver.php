<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\UnionType\GenericTagDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType\GenericTagDeleteMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericTagDeleteMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericTagDeleteMetaMutationErrorPayloadUnionTypeResolver $genericTagDeleteMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericTagDeleteMetaMutationErrorPayloadUnionTypeResolver(): GenericTagDeleteMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericTagDeleteMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericTagDeleteMetaMutationErrorPayloadUnionTypeResolver */
            $genericTagDeleteMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericTagDeleteMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericTagDeleteMetaMutationErrorPayloadUnionTypeResolver = $genericTagDeleteMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericTagDeleteMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericTagDeleteMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericTagDeleteMetaMutationErrorPayloadUnionTypeResolver();
    }
}
