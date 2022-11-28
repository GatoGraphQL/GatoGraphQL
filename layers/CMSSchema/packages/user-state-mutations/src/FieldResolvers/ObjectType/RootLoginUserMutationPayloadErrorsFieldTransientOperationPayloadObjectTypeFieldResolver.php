<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\RootLoginUserMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\RootLoginUserMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootLoginUserMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootLoginUserMutationErrorPayloadUnionTypeResolver $rootLoginUserMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootLoginUserMutationErrorPayloadUnionTypeResolver(RootLoginUserMutationErrorPayloadUnionTypeResolver $rootLoginUserMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootLoginUserMutationErrorPayloadUnionTypeResolver = $rootLoginUserMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootLoginUserMutationErrorPayloadUnionTypeResolver(): RootLoginUserMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootLoginUserMutationErrorPayloadUnionTypeResolver */
        return $this->rootLoginUserMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(RootLoginUserMutationErrorPayloadUnionTypeResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootLoginUserMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootLoginUserMutationErrorPayloadUnionTypeResolver();
    }
}
