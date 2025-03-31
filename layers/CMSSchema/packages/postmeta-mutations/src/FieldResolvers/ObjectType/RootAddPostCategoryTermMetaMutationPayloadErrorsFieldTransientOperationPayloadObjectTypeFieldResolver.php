<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\RootAddPostTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootAddPostTermMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootAddPostTermMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootAddPostTermMetaMutationErrorPayloadUnionTypeResolver $rootAddPostTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddPostTermMetaMutationErrorPayloadUnionTypeResolver(): RootAddPostTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddPostTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddPostTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddPostTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddPostTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddPostTermMetaMutationErrorPayloadUnionTypeResolver = $rootAddPostTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddPostTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootAddPostTermMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootAddPostTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
