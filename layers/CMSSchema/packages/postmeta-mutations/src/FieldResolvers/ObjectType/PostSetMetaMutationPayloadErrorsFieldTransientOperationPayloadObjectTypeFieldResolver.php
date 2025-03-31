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
    private ?PostSetMetaMutationErrorPayloadUnionTypeResolver $postCustomPostSetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostSetMetaMutationErrorPayloadUnionTypeResolver(): PostSetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCustomPostSetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostSetMetaMutationErrorPayloadUnionTypeResolver */
            $postCustomPostSetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostSetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postCustomPostSetMetaMutationErrorPayloadUnionTypeResolver = $postCustomPostSetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postCustomPostSetMetaMutationErrorPayloadUnionTypeResolver;
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
