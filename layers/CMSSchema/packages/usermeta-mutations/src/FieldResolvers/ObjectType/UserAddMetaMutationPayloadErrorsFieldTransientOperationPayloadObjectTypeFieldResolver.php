<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\UserAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\UserAddMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class UserAddMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?UserAddMetaMutationErrorPayloadUnionTypeResolver $userAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getUserAddMetaMutationErrorPayloadUnionTypeResolver(): UserAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->userAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var UserAddMetaMutationErrorPayloadUnionTypeResolver */
            $userAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(UserAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->userAddMetaMutationErrorPayloadUnionTypeResolver = $userAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->userAddMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserAddMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getUserAddMetaMutationErrorPayloadUnionTypeResolver();
    }
}
