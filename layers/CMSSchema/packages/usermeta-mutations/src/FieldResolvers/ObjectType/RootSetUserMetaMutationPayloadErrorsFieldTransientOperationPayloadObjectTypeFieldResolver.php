<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\RootSetUserMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\RootSetUserMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootSetUserMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootSetUserMetaMutationErrorPayloadUnionTypeResolver $rootSetUserMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetUserMetaMutationErrorPayloadUnionTypeResolver(): RootSetUserMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetUserMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetUserMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetUserMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetUserMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetUserMetaMutationErrorPayloadUnionTypeResolver = $rootSetUserMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetUserMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootSetUserMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootSetUserMetaMutationErrorPayloadUnionTypeResolver();
    }
}
