<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\GenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\GenericCustomPostUpdateMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCustomPostUpdateMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver $genericCustomPostUpdateMutationErrorPayloadUnionTypeResolver = null;

    final public function setGenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver(GenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver $genericCustomPostUpdateMutationErrorPayloadUnionTypeResolver): void
    {
        $this->genericCustomPostUpdateMutationErrorPayloadUnionTypeResolver = $genericCustomPostUpdateMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getGenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver(): GenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCustomPostUpdateMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver */
            $genericCustomPostUpdateMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCustomPostUpdateMutationErrorPayloadUnionTypeResolver = $genericCustomPostUpdateMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCustomPostUpdateMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericCustomPostUpdateMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
