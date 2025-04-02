<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\UserSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\UserSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class UserSetMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?UserSetMetaMutationErrorPayloadUnionTypeResolver $userSetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getUserSetMetaMutationErrorPayloadUnionTypeResolver(): UserSetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->userSetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var UserSetMetaMutationErrorPayloadUnionTypeResolver */
            $userSetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(UserSetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->userSetMetaMutationErrorPayloadUnionTypeResolver = $userSetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->userSetMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserSetMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getUserSetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
