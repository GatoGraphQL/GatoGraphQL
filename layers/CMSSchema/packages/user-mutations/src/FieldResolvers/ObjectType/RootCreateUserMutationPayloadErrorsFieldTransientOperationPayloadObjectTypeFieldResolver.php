<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\RootCreateUserMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\RootCreateUserMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootCreateUserMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootCreateUserMutationErrorPayloadUnionTypeResolver $rootCreateUserMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootCreateUserMutationErrorPayloadUnionTypeResolver(): RootCreateUserMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootCreateUserMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootCreateUserMutationErrorPayloadUnionTypeResolver */
            $rootCreateUserMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootCreateUserMutationErrorPayloadUnionTypeResolver::class);
            $this->rootCreateUserMutationErrorPayloadUnionTypeResolver = $rootCreateUserMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootCreateUserMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreateUserMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootCreateUserMutationErrorPayloadUnionTypeResolver();
    }
}
