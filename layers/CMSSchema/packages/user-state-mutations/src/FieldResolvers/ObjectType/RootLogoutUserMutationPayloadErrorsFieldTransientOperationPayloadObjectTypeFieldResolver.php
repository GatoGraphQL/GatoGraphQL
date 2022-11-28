<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\RootLogoutUserMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\RootLogoutUserMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootLogoutUserMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootLogoutUserMutationErrorPayloadUnionTypeResolver $rootLogoutUserMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootLogoutUserMutationErrorPayloadUnionTypeResolver(RootLogoutUserMutationErrorPayloadUnionTypeResolver $rootLogoutUserMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootLogoutUserMutationErrorPayloadUnionTypeResolver = $rootLogoutUserMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootLogoutUserMutationErrorPayloadUnionTypeResolver(): RootLogoutUserMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootLogoutUserMutationErrorPayloadUnionTypeResolver */
        return $this->rootLogoutUserMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(RootLogoutUserMutationErrorPayloadUnionTypeResolver::class);
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
