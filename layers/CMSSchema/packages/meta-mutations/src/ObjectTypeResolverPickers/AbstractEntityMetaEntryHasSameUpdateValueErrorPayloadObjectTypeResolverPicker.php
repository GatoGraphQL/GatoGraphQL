<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MetaMutations\ObjectModels\EntityMetaEntryHasSameUpdateValueErrorPayload;
use PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType\EntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractEntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?EntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeResolver $entityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeResolver = null;

    final protected function getEntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeResolver(): EntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeResolver
    {
        if ($this->entityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeResolver === null) {
            /** @var EntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeResolver */
            $entityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(EntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeResolver::class);
            $this->entityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeResolver = $entityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeResolver;
        }
        return $this->entityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getEntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return EntityMetaEntryHasSameUpdateValueErrorPayload::class;
    }
}
