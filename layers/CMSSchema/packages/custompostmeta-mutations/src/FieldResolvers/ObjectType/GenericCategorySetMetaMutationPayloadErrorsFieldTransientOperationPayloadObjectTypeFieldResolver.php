<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType\GenericCustomPostSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\GenericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCustomPostSetMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver $genericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver(): GenericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver */
            $genericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver = $genericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericCustomPostSetMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
