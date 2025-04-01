<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\GenericUserSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\GenericUserSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericUserSetMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericUserSetMetaMutationErrorPayloadUnionTypeResolver $genericUserSetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericUserSetMetaMutationErrorPayloadUnionTypeResolver(): GenericUserSetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericUserSetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericUserSetMetaMutationErrorPayloadUnionTypeResolver */
            $genericUserSetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericUserSetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericUserSetMetaMutationErrorPayloadUnionTypeResolver = $genericUserSetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericUserSetMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericUserSetMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericUserSetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
