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
    private ?PostAddMetaMutationErrorPayloadUnionTypeResolver $postCustomPostAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostAddMetaMutationErrorPayloadUnionTypeResolver(): PostAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCustomPostAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostAddMetaMutationErrorPayloadUnionTypeResolver */
            $postCustomPostAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postCustomPostAddMetaMutationErrorPayloadUnionTypeResolver = $postCustomPostAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postCustomPostAddMetaMutationErrorPayloadUnionTypeResolver;
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
