<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\UserUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\UserUpdateMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class UserUpdateMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?UserUpdateMetaMutationErrorPayloadUnionTypeResolver $userUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getUserUpdateMetaMutationErrorPayloadUnionTypeResolver(): UserUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->userUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var UserUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $userUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(UserUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->userUpdateMetaMutationErrorPayloadUnionTypeResolver = $userUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->userUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserUpdateMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getUserUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
