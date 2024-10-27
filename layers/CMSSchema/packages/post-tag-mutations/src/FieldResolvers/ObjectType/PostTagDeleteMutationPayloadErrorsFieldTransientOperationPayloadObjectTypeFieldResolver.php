<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\PostTagDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType\PostTagDeleteMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostTagDeleteMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PostTagDeleteMutationErrorPayloadUnionTypeResolver $postTagDeleteMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostTagDeleteMutationErrorPayloadUnionTypeResolver(): PostTagDeleteMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postTagDeleteMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostTagDeleteMutationErrorPayloadUnionTypeResolver */
            $postTagDeleteMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostTagDeleteMutationErrorPayloadUnionTypeResolver::class);
            $this->postTagDeleteMutationErrorPayloadUnionTypeResolver = $postTagDeleteMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postTagDeleteMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostTagDeleteMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPostTagDeleteMutationErrorPayloadUnionTypeResolver();
    }
}
