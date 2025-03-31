<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\UnionType\RootAddPostTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\ObjectType\RootAddPostTagTermMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootAddPostTagTermMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootAddPostTagTermMetaMutationErrorPayloadUnionTypeResolver $rootAddPostTagTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddPostTagTermMetaMutationErrorPayloadUnionTypeResolver(): RootAddPostTagTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddPostTagTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddPostTagTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddPostTagTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddPostTagTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddPostTagTermMetaMutationErrorPayloadUnionTypeResolver = $rootAddPostTagTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddPostTagTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootAddPostTagTermMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootAddPostTagTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
