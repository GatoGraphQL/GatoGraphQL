<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType\GenericTagSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\UnionType\GenericTagSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericTagSetMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericTagSetMetaMutationErrorPayloadUnionTypeResolver $genericTagSetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericTagSetMetaMutationErrorPayloadUnionTypeResolver(): GenericTagSetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericTagSetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericTagSetMetaMutationErrorPayloadUnionTypeResolver */
            $genericTagSetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericTagSetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericTagSetMetaMutationErrorPayloadUnionTypeResolver = $genericTagSetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericTagSetMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericTagSetMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericTagSetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
