<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MetaMutations\ObjectModels\EntityMetaKeyDoesNotExistErrorPayload;
use PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType\EntityMetaKeyDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractEntityMetaKeyDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?EntityMetaKeyDoesNotExistErrorPayloadObjectTypeResolver $entityMetaKeyDoesNotExistErrorPayloadObjectTypeResolver = null;

    final protected function getEntityMetaKeyDoesNotExistErrorPayloadObjectTypeResolver(): EntityMetaKeyDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->entityMetaKeyDoesNotExistErrorPayloadObjectTypeResolver === null) {
            /** @var EntityMetaKeyDoesNotExistErrorPayloadObjectTypeResolver */
            $entityMetaKeyDoesNotExistErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(EntityMetaKeyDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->entityMetaKeyDoesNotExistErrorPayloadObjectTypeResolver = $entityMetaKeyDoesNotExistErrorPayloadObjectTypeResolver;
        }
        return $this->entityMetaKeyDoesNotExistErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getEntityMetaKeyDoesNotExistErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return EntityMetaKeyDoesNotExistErrorPayload::class;
    }
}
