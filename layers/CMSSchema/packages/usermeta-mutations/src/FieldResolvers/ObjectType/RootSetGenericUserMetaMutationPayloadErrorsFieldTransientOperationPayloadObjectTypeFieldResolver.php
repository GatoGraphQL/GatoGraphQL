<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\RootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\RootSetGenericUserMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootSetGenericUserMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver $rootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver(): RootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver = $rootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootSetGenericUserMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver();
    }
}
