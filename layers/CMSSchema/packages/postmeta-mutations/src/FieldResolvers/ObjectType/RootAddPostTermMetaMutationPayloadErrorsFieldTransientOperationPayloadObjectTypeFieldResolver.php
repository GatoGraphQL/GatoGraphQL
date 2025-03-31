<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\RootAddPostMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootAddPostMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootAddPostMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootAddPostMetaMutationErrorPayloadUnionTypeResolver $rootAddPostMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddPostMetaMutationErrorPayloadUnionTypeResolver(): RootAddPostMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddPostMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddPostMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddPostMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddPostMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddPostMetaMutationErrorPayloadUnionTypeResolver = $rootAddPostMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddPostMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootAddPostMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootAddPostMetaMutationErrorPayloadUnionTypeResolver();
    }
}
