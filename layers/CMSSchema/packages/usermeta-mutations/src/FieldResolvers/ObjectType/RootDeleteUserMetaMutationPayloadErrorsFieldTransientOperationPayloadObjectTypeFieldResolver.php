<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\RootDeleteUserMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\RootDeleteUserMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeleteUserMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeleteUserMetaMutationErrorPayloadUnionTypeResolver $rootDeleteUserMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteUserMetaMutationErrorPayloadUnionTypeResolver(): RootDeleteUserMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteUserMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteUserMetaMutationErrorPayloadUnionTypeResolver */
            $rootDeleteUserMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteUserMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteUserMetaMutationErrorPayloadUnionTypeResolver = $rootDeleteUserMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteUserMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeleteUserMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeleteUserMetaMutationErrorPayloadUnionTypeResolver();
    }
}
