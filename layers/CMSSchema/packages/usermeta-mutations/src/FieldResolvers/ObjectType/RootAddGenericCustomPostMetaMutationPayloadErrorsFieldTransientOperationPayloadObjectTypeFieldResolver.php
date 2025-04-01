<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\RootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\RootAddGenericUserMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootAddGenericUserMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver $rootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver(): RootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver = $rootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootAddGenericUserMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver();
    }
}
