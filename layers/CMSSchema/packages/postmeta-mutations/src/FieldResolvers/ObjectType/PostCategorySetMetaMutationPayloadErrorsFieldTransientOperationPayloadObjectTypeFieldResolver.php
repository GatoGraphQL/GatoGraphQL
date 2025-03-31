<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\PostSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\PostSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostSetMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PostSetMetaMutationErrorPayloadUnionTypeResolver $postCategorySetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostSetMetaMutationErrorPayloadUnionTypeResolver(): PostSetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCategorySetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostSetMetaMutationErrorPayloadUnionTypeResolver */
            $postCategorySetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostSetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postCategorySetMetaMutationErrorPayloadUnionTypeResolver = $postCategorySetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postCategorySetMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostSetMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPostSetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
