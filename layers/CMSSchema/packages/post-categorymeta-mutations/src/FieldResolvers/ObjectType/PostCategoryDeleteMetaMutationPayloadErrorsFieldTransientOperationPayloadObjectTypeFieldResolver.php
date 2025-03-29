<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\UnionType\PostCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\ObjectType\PostCategoryDeleteMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostCategoryDeleteMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PostCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver $postCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver(): PostCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver */
            $postCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver = $postCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostCategoryDeleteMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPostCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver();
    }
}
