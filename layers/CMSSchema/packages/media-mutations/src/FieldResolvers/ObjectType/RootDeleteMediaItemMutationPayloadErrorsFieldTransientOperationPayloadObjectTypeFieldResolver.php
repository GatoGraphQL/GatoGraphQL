<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\RootDeleteMediaItemMutationPayloadObjectTypeResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\RootDeleteMediaItemMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeleteMediaItemMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeleteMediaItemMutationErrorPayloadUnionTypeResolver $rootDeleteMediaItemMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteMediaItemMutationErrorPayloadUnionTypeResolver(): RootDeleteMediaItemMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteMediaItemMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteMediaItemMutationErrorPayloadUnionTypeResolver */
            $rootDeleteMediaItemMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteMediaItemMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteMediaItemMutationErrorPayloadUnionTypeResolver = $rootDeleteMediaItemMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteMediaItemMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeleteMediaItemMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeleteMediaItemMutationErrorPayloadUnionTypeResolver();
    }
}
