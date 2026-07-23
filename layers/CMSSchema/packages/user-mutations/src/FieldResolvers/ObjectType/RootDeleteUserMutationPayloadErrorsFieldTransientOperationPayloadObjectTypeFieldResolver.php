<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\RootDeleteUserMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\RootDeleteUserMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeleteUserMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeleteUserMutationErrorPayloadUnionTypeResolver $rootDeleteUserMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteUserMutationErrorPayloadUnionTypeResolver(): RootDeleteUserMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteUserMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteUserMutationErrorPayloadUnionTypeResolver */
            $rootDeleteUserMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteUserMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteUserMutationErrorPayloadUnionTypeResolver = $rootDeleteUserMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteUserMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeleteUserMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeleteUserMutationErrorPayloadUnionTypeResolver();
    }
}
