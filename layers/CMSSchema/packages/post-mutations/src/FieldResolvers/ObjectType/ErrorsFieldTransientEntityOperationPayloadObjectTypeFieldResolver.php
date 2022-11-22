<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientEntityOperationPayloadObjectTypeFieldResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\GenericErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class ErrorsFieldTransientEntityOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientEntityOperationPayloadObjectTypeFieldResolver
{
    private ?GenericErrorPayloadObjectTypeResolver $genericErrorPayloadObjectTypeResolver = null;

    final public function setGenericErrorPayloadObjectTypeResolver(GenericErrorPayloadObjectTypeResolver $genericErrorPayloadObjectTypeResolver): void
    {
        $this->genericErrorPayloadObjectTypeResolver = $genericErrorPayloadObjectTypeResolver;
    }
    final protected function getGenericErrorPayloadObjectTypeResolver(): GenericErrorPayloadObjectTypeResolver
    {
        /** @var GenericErrorPayloadObjectTypeResolver */
        return $this->genericErrorPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(GenericErrorPayloadObjectTypeResolver::class);
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericErrorPayloadObjectTypeResolver();
    }
}
