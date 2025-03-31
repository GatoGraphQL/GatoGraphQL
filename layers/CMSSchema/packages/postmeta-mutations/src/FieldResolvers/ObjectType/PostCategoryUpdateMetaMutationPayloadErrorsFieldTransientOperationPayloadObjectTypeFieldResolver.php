<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\PostUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\PostUpdateMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostUpdateMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PostUpdateMetaMutationErrorPayloadUnionTypeResolver $postCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostUpdateMetaMutationErrorPayloadUnionTypeResolver(): PostUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $postCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver = $postCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostUpdateMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPostUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
