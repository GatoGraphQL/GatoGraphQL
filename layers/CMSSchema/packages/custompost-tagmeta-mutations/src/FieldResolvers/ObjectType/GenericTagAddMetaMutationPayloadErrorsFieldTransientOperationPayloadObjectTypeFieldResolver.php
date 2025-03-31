<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\UnionType\GenericTagAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType\GenericTagAddMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericTagAddMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericTagAddMetaMutationErrorPayloadUnionTypeResolver $genericTagAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericTagAddMetaMutationErrorPayloadUnionTypeResolver(): GenericTagAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericTagAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericTagAddMetaMutationErrorPayloadUnionTypeResolver */
            $genericTagAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericTagAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericTagAddMetaMutationErrorPayloadUnionTypeResolver = $genericTagAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericTagAddMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericTagAddMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericTagAddMetaMutationErrorPayloadUnionTypeResolver();
    }
}
