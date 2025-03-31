<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\UnionType\PostTagUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\ObjectType\PostTagUpdateMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostTagUpdateMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PostTagUpdateMetaMutationErrorPayloadUnionTypeResolver $postTagUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostTagUpdateMetaMutationErrorPayloadUnionTypeResolver(): PostTagUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postTagUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostTagUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $postTagUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostTagUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postTagUpdateMetaMutationErrorPayloadUnionTypeResolver = $postTagUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postTagUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostTagUpdateMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPostTagUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
