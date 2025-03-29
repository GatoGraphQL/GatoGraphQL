<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\UnionType\PostCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\ObjectType\PostCategoryUpdateMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostCategoryUpdateMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PostCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver $postCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver(): PostCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $postCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver = $postCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostCategoryUpdateMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPostCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
