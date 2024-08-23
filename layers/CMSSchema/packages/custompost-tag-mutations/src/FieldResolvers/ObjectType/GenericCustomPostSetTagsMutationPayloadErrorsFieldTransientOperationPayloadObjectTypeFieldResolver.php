<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\ObjectType\GenericCustomPostSetTagsMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\GenericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCustomPostSetTagsMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver $genericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver = null;

    final public function setGenericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver(GenericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver $genericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver): void
    {
        $this->genericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver = $genericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getGenericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver(): GenericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver */
            $genericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver = $genericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericCustomPostSetTagsMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver();
    }
}
