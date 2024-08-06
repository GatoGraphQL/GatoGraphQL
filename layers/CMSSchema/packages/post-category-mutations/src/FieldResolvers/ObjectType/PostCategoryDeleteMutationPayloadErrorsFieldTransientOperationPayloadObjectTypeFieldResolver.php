<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\PostCategoryDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\ObjectType\PostCategoryDeleteMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostCategoryDeleteMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PostCategoryDeleteMutationErrorPayloadUnionTypeResolver $postCategoryDeleteMutationErrorPayloadUnionTypeResolver = null;

    final public function setPostCategoryDeleteMutationErrorPayloadUnionTypeResolver(PostCategoryDeleteMutationErrorPayloadUnionTypeResolver $postCategoryDeleteMutationErrorPayloadUnionTypeResolver): void
    {
        $this->postCategoryDeleteMutationErrorPayloadUnionTypeResolver = $postCategoryDeleteMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getPostCategoryDeleteMutationErrorPayloadUnionTypeResolver(): PostCategoryDeleteMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCategoryDeleteMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostCategoryDeleteMutationErrorPayloadUnionTypeResolver */
            $postCategoryDeleteMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostCategoryDeleteMutationErrorPayloadUnionTypeResolver::class);
            $this->postCategoryDeleteMutationErrorPayloadUnionTypeResolver = $postCategoryDeleteMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postCategoryDeleteMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostCategoryDeleteMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPostCategoryDeleteMutationErrorPayloadUnionTypeResolver();
    }
}
