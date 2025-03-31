<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\UnionType\GenericTagUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType\GenericTagUpdateMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericTagUpdateMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericTagUpdateMetaMutationErrorPayloadUnionTypeResolver $genericTagUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericTagUpdateMetaMutationErrorPayloadUnionTypeResolver(): GenericTagUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericTagUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericTagUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $genericTagUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericTagUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericTagUpdateMetaMutationErrorPayloadUnionTypeResolver = $genericTagUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericTagUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericTagUpdateMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericTagUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
