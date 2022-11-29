<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\CustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class CustomPostRemoveFeaturedImageMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver $customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver = null;

    final public function setCustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver(CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver $customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver): void
    {
        $this->customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver = $customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getCustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver(): CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver
    {
        /** @var CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver */
        return $this->customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            CustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getCustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver();
    }
}
