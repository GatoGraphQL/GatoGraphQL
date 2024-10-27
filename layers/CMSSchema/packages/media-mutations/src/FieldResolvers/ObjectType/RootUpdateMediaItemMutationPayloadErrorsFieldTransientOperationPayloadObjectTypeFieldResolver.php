<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\RootUpdateMediaItemMutationPayloadObjectTypeResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\RootUpdateMediaItemMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdateMediaItemMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdateMediaItemMutationErrorPayloadUnionTypeResolver $rootUpdateMediaItemMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateMediaItemMutationErrorPayloadUnionTypeResolver(): RootUpdateMediaItemMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateMediaItemMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateMediaItemMutationErrorPayloadUnionTypeResolver */
            $rootUpdateMediaItemMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateMediaItemMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateMediaItemMutationErrorPayloadUnionTypeResolver = $rootUpdateMediaItemMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateMediaItemMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdateMediaItemMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdateMediaItemMutationErrorPayloadUnionTypeResolver();
    }
}
