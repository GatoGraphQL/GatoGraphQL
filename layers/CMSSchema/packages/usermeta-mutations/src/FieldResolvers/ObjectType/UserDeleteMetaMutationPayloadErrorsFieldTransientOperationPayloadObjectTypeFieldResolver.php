<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\UserDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\UserDeleteMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class UserDeleteMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?UserDeleteMetaMutationErrorPayloadUnionTypeResolver $userDeleteMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getUserDeleteMetaMutationErrorPayloadUnionTypeResolver(): UserDeleteMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->userDeleteMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var UserDeleteMetaMutationErrorPayloadUnionTypeResolver */
            $userDeleteMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(UserDeleteMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->userDeleteMetaMutationErrorPayloadUnionTypeResolver = $userDeleteMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->userDeleteMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserDeleteMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getUserDeleteMetaMutationErrorPayloadUnionTypeResolver();
    }
}
