<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\RootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootDeletePostTermMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeletePostTermMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver $rootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver(): RootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver = $rootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeletePostTermMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
