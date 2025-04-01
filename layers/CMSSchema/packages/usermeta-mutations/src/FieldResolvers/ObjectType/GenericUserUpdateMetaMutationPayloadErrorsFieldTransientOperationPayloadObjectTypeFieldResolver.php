<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\GenericUserUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\GenericUserUpdateMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericUserUpdateMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericUserUpdateMetaMutationErrorPayloadUnionTypeResolver $genericUserUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericUserUpdateMetaMutationErrorPayloadUnionTypeResolver(): GenericUserUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericUserUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericUserUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $genericUserUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericUserUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericUserUpdateMetaMutationErrorPayloadUnionTypeResolver = $genericUserUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericUserUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericUserUpdateMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericUserUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
