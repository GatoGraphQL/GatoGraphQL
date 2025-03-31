<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\ObjectType\PostTagSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\UnionType\PostTagSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostTagSetMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PostTagSetMetaMutationErrorPayloadUnionTypeResolver $postTagSetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostTagSetMetaMutationErrorPayloadUnionTypeResolver(): PostTagSetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postTagSetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostTagSetMetaMutationErrorPayloadUnionTypeResolver */
            $postTagSetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostTagSetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postTagSetMetaMutationErrorPayloadUnionTypeResolver = $postTagSetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postTagSetMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostTagSetMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPostTagSetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
