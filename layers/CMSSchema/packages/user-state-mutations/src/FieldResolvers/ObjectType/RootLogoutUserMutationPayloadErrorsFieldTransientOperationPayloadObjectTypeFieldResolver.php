<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserStateMutations\TypeResolvers\UnionType\RootLogoutUserMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\RootLogoutUserMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootLogoutUserMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootLogoutUserMutationErrorPayloadUnionTypeResolver $rootLogoutUserMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootLogoutUserMutationErrorPayloadUnionTypeResolver(): RootLogoutUserMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootLogoutUserMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootLogoutUserMutationErrorPayloadUnionTypeResolver */
            $rootLogoutUserMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootLogoutUserMutationErrorPayloadUnionTypeResolver::class);
            $this->rootLogoutUserMutationErrorPayloadUnionTypeResolver = $rootLogoutUserMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootLogoutUserMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootLogoutUserMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootLogoutUserMutationErrorPayloadUnionTypeResolver();
    }
}
