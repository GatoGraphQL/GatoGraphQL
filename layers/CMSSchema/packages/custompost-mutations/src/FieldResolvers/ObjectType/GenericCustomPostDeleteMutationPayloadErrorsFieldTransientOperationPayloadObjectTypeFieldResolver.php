<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\GenericCustomPostDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\GenericCustomPostDeleteMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCustomPostDeleteMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericCustomPostDeleteMutationErrorPayloadUnionTypeResolver $genericCustomPostDeleteMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCustomPostDeleteMutationErrorPayloadUnionTypeResolver(): GenericCustomPostDeleteMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCustomPostDeleteMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCustomPostDeleteMutationErrorPayloadUnionTypeResolver */
            $genericCustomPostDeleteMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCustomPostDeleteMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCustomPostDeleteMutationErrorPayloadUnionTypeResolver = $genericCustomPostDeleteMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCustomPostDeleteMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericCustomPostDeleteMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericCustomPostDeleteMutationErrorPayloadUnionTypeResolver();
    }
}
