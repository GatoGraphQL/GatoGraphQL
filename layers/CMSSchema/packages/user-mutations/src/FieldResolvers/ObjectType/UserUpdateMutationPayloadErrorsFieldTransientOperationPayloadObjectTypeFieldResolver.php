<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\UserUpdateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\UserUpdateMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class UserUpdateMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?UserUpdateMutationErrorPayloadUnionTypeResolver $userUpdateMutationErrorPayloadUnionTypeResolver = null;

    final protected function getUserUpdateMutationErrorPayloadUnionTypeResolver(): UserUpdateMutationErrorPayloadUnionTypeResolver
    {
        if ($this->userUpdateMutationErrorPayloadUnionTypeResolver === null) {
            /** @var UserUpdateMutationErrorPayloadUnionTypeResolver */
            $userUpdateMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(UserUpdateMutationErrorPayloadUnionTypeResolver::class);
            $this->userUpdateMutationErrorPayloadUnionTypeResolver = $userUpdateMutationErrorPayloadUnionTypeResolver;
        }
        return $this->userUpdateMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserUpdateMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getUserUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
