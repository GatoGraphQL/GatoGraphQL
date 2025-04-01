<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\GenericUserDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\GenericUserDeleteMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericUserDeleteMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericUserDeleteMetaMutationErrorPayloadUnionTypeResolver $genericUserDeleteMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericUserDeleteMetaMutationErrorPayloadUnionTypeResolver(): GenericUserDeleteMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericUserDeleteMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericUserDeleteMetaMutationErrorPayloadUnionTypeResolver */
            $genericUserDeleteMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericUserDeleteMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericUserDeleteMetaMutationErrorPayloadUnionTypeResolver = $genericUserDeleteMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericUserDeleteMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericUserDeleteMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericUserDeleteMetaMutationErrorPayloadUnionTypeResolver();
    }
}
