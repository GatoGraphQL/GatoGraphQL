<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\UserDeleteMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\UserDeleteMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class UserDeleteMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?UserDeleteMutationErrorPayloadUnionTypeResolver $userDeleteMutationErrorPayloadUnionTypeResolver = null;

    final protected function getUserDeleteMutationErrorPayloadUnionTypeResolver(): UserDeleteMutationErrorPayloadUnionTypeResolver
    {
        if ($this->userDeleteMutationErrorPayloadUnionTypeResolver === null) {
            /** @var UserDeleteMutationErrorPayloadUnionTypeResolver */
            $userDeleteMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(UserDeleteMutationErrorPayloadUnionTypeResolver::class);
            $this->userDeleteMutationErrorPayloadUnionTypeResolver = $userDeleteMutationErrorPayloadUnionTypeResolver;
        }
        return $this->userDeleteMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserDeleteMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getUserDeleteMutationErrorPayloadUnionTypeResolver();
    }
}
