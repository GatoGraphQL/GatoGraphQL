<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\GenericTagUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\ObjectType\GenericTagUpdateMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericTagUpdateMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericTagUpdateMutationErrorPayloadUnionTypeResolver $genericTagUpdateMutationErrorPayloadUnionTypeResolver = null;

    final public function setGenericTagUpdateMutationErrorPayloadUnionTypeResolver(GenericTagUpdateMutationErrorPayloadUnionTypeResolver $genericTagUpdateMutationErrorPayloadUnionTypeResolver): void
    {
        $this->genericTagUpdateMutationErrorPayloadUnionTypeResolver = $genericTagUpdateMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getGenericTagUpdateMutationErrorPayloadUnionTypeResolver(): GenericTagUpdateMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericTagUpdateMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericTagUpdateMutationErrorPayloadUnionTypeResolver */
            $genericTagUpdateMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericTagUpdateMutationErrorPayloadUnionTypeResolver::class);
            $this->genericTagUpdateMutationErrorPayloadUnionTypeResolver = $genericTagUpdateMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericTagUpdateMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericTagUpdateMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericTagUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
