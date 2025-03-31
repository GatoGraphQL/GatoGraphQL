<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\RootDeletePostMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootDeletePostMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeletePostMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeletePostMetaMutationErrorPayloadUnionTypeResolver $rootDeletePostMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeletePostMetaMutationErrorPayloadUnionTypeResolver(): RootDeletePostMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeletePostMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeletePostMetaMutationErrorPayloadUnionTypeResolver */
            $rootDeletePostMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeletePostMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeletePostMetaMutationErrorPayloadUnionTypeResolver = $rootDeletePostMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeletePostMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeletePostMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeletePostMetaMutationErrorPayloadUnionTypeResolver();
    }
}
