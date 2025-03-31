<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\ObjectType\PostCategorySetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\UnionType\PostCategorySetMetaMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostCategorySetMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PostCategorySetMetaMutationErrorPayloadUnionTypeResolver $postCategorySetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostCategorySetMetaMutationErrorPayloadUnionTypeResolver(): PostCategorySetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCategorySetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostCategorySetMetaMutationErrorPayloadUnionTypeResolver */
            $postCategorySetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostCategorySetMetaMutationErrorPayloadUnionTypeResolver::class);
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
            PostCategorySetMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPostCategorySetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
