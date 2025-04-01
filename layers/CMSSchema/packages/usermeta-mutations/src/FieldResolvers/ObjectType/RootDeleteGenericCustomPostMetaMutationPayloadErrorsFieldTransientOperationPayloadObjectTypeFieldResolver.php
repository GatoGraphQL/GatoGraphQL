<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\RootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\RootDeleteGenericUserMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeleteGenericUserMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver $rootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver(): RootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver */
            $rootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver = $rootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeleteGenericUserMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver();
    }
}
