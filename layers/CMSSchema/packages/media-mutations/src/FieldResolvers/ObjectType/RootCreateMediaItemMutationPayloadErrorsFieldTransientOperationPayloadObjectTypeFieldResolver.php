<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\RootCreateMediaItemMutationPayloadObjectTypeResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\RootCreateMediaItemMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootCreateMediaItemMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootCreateMediaItemMutationErrorPayloadUnionTypeResolver $rootCreateMediaItemMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootCreateMediaItemMutationErrorPayloadUnionTypeResolver(RootCreateMediaItemMutationErrorPayloadUnionTypeResolver $rootCreateMediaItemMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootCreateMediaItemMutationErrorPayloadUnionTypeResolver = $rootCreateMediaItemMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootCreateMediaItemMutationErrorPayloadUnionTypeResolver(): RootCreateMediaItemMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootCreateMediaItemMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootCreateMediaItemMutationErrorPayloadUnionTypeResolver */
            $rootCreateMediaItemMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootCreateMediaItemMutationErrorPayloadUnionTypeResolver::class);
            $this->rootCreateMediaItemMutationErrorPayloadUnionTypeResolver = $rootCreateMediaItemMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootCreateMediaItemMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreateMediaItemMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootCreateMediaItemMutationErrorPayloadUnionTypeResolver();
    }
}
