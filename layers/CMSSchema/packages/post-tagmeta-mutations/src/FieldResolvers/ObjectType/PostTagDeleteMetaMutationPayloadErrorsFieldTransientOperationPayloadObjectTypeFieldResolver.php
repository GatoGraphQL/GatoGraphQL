<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\UnionType\PostTagDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\ObjectType\PostTagDeleteMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostTagDeleteMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PostTagDeleteMetaMutationErrorPayloadUnionTypeResolver $postTagDeleteMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostTagDeleteMetaMutationErrorPayloadUnionTypeResolver(): PostTagDeleteMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postTagDeleteMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostTagDeleteMetaMutationErrorPayloadUnionTypeResolver */
            $postTagDeleteMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostTagDeleteMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postTagDeleteMetaMutationErrorPayloadUnionTypeResolver = $postTagDeleteMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postTagDeleteMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostTagDeleteMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPostTagDeleteMetaMutationErrorPayloadUnionTypeResolver();
    }
}
