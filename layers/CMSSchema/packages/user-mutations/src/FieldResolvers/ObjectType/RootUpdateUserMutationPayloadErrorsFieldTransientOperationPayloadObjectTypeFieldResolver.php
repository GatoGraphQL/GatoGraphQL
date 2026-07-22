<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\RootUpdateUserMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\RootUpdateUserMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdateUserMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdateUserMutationErrorPayloadUnionTypeResolver $rootUpdateUserMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateUserMutationErrorPayloadUnionTypeResolver(): RootUpdateUserMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateUserMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateUserMutationErrorPayloadUnionTypeResolver */
            $rootUpdateUserMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateUserMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateUserMutationErrorPayloadUnionTypeResolver = $rootUpdateUserMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateUserMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdateUserMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdateUserMutationErrorPayloadUnionTypeResolver();
    }
}
