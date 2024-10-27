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

    final protected function getCustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver(): CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver
    {
        if ($this->customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver === null) {
            /** @var CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver */
            $customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver::class);
            $this->customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver = $customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver;
        }
        return $this->customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver;
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
