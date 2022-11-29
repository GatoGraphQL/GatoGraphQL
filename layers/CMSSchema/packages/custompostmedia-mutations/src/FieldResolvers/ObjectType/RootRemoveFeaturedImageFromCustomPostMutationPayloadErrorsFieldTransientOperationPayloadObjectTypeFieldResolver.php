<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootRemoveFeaturedImageFromCustomPostMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver $rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver(RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver $rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver = $rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver(): RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver */
        return $this->rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
