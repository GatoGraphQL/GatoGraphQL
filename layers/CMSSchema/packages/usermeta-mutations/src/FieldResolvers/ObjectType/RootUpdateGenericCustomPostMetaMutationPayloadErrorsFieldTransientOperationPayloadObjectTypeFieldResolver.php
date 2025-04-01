<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\RootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\RootUpdateGenericUserMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdateGenericUserMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver $rootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver(): RootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver */
            $rootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver = $rootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdateGenericUserMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver();
    }
}
