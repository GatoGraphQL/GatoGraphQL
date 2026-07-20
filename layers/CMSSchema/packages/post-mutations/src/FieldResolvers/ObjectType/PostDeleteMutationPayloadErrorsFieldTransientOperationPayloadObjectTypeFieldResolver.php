<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\PostDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\PostDeleteMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostDeleteMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PostDeleteMutationErrorPayloadUnionTypeResolver $postDeleteMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostDeleteMutationErrorPayloadUnionTypeResolver(): PostDeleteMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postDeleteMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostDeleteMutationErrorPayloadUnionTypeResolver */
            $postDeleteMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostDeleteMutationErrorPayloadUnionTypeResolver::class);
            $this->postDeleteMutationErrorPayloadUnionTypeResolver = $postDeleteMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postDeleteMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostDeleteMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPostDeleteMutationErrorPayloadUnionTypeResolver();
    }
}
