<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\GenericUserAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\GenericUserAddMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericUserAddMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericUserAddMetaMutationErrorPayloadUnionTypeResolver $genericUserAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericUserAddMetaMutationErrorPayloadUnionTypeResolver(): GenericUserAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericUserAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericUserAddMetaMutationErrorPayloadUnionTypeResolver */
            $genericUserAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericUserAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericUserAddMetaMutationErrorPayloadUnionTypeResolver = $genericUserAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericUserAddMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericUserAddMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericUserAddMetaMutationErrorPayloadUnionTypeResolver();
    }
}
