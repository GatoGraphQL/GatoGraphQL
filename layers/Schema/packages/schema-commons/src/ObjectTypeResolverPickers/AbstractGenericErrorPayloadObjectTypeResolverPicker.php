<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ObjectTypeResolverPickers;

use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\GenericErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;

abstract class AbstractGenericErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker implements GenericErrorPayloadObjectTypeResolverPickerInterface
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

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getGenericErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return GenericErrorPayload::class;
    }
}
