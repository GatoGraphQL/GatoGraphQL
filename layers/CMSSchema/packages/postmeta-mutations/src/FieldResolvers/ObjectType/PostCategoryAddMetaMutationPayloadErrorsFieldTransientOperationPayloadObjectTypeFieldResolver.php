<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\PostAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\PostAddMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostAddMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PostAddMetaMutationErrorPayloadUnionTypeResolver $postCategoryAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostAddMetaMutationErrorPayloadUnionTypeResolver(): PostAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCategoryAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostAddMetaMutationErrorPayloadUnionTypeResolver */
            $postCategoryAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postCategoryAddMetaMutationErrorPayloadUnionTypeResolver = $postCategoryAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postCategoryAddMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostAddMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPostAddMetaMutationErrorPayloadUnionTypeResolver();
    }
}
