<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\PostUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\PostUpdateMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostUpdateMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PostUpdateMutationErrorPayloadUnionTypeResolver $postUpdateMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostUpdateMutationErrorPayloadUnionTypeResolver(): PostUpdateMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postUpdateMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostUpdateMutationErrorPayloadUnionTypeResolver */
            $postUpdateMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostUpdateMutationErrorPayloadUnionTypeResolver::class);
            $this->postUpdateMutationErrorPayloadUnionTypeResolver = $postUpdateMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postUpdateMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostUpdateMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPostUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
