<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\UnionType\PostTagAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\ObjectType\PostTagAddMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostTagAddMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PostTagAddMetaMutationErrorPayloadUnionTypeResolver $postTagAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostTagAddMetaMutationErrorPayloadUnionTypeResolver(): PostTagAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postTagAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostTagAddMetaMutationErrorPayloadUnionTypeResolver */
            $postTagAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostTagAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postTagAddMetaMutationErrorPayloadUnionTypeResolver = $postTagAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postTagAddMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostTagAddMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPostTagAddMetaMutationErrorPayloadUnionTypeResolver();
    }
}
