<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\PostDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\PostDeleteMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostDeleteMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?PostDeleteMetaMutationErrorPayloadUnionTypeResolver $postCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostDeleteMetaMutationErrorPayloadUnionTypeResolver(): PostDeleteMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostDeleteMetaMutationErrorPayloadUnionTypeResolver */
            $postCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostDeleteMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver = $postCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostDeleteMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getPostDeleteMetaMutationErrorPayloadUnionTypeResolver();
    }
}
