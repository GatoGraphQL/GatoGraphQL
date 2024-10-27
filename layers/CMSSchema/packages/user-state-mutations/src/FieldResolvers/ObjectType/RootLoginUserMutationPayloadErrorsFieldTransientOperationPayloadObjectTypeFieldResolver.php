<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserStateMutations\TypeResolvers\UnionType\RootLoginUserMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\RootLoginUserMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootLoginUserMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootLoginUserMutationErrorPayloadUnionTypeResolver $rootLoginUserMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootLoginUserMutationErrorPayloadUnionTypeResolver(): RootLoginUserMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootLoginUserMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootLoginUserMutationErrorPayloadUnionTypeResolver */
            $rootLoginUserMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootLoginUserMutationErrorPayloadUnionTypeResolver::class);
            $this->rootLoginUserMutationErrorPayloadUnionTypeResolver = $rootLoginUserMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootLoginUserMutationErrorPayloadUnionTypeResolver;
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
