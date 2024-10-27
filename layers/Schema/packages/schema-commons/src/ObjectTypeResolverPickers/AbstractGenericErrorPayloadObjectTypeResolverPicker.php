<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ObjectTypeResolverPickers;

use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\GenericErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;

abstract class AbstractGenericErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker implements GenericErrorPayloadObjectTypeResolverPickerInterface
{
    private ?GenericErrorPayloadObjectTypeResolver $genericErrorPayloadObjectTypeResolver = null;

    final protected function getGenericErrorPayloadObjectTypeResolver(): GenericErrorPayloadObjectTypeResolver
    {
        if ($this->genericErrorPayloadObjectTypeResolver === null) {
            /** @var GenericErrorPayloadObjectTypeResolver */
            $genericErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericErrorPayloadObjectTypeResolver::class);
            $this->genericErrorPayloadObjectTypeResolver = $genericErrorPayloadObjectTypeResolver;
        }
        return $this->genericErrorPayloadObjectTypeResolver;
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
