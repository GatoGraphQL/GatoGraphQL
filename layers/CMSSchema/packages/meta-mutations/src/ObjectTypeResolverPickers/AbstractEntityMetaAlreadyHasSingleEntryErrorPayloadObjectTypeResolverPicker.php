<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MetaMutations\ObjectModels\EntityMetaAlreadyHasSingleEntryErrorPayload;
use PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType\EntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractEntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?EntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver $entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = null;

    final protected function getEntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver(): EntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver
    {
        if ($this->entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver === null) {
            /** @var EntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver */
            $entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(EntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver::class);
            $this->entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = $entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
        }
        return $this->entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getEntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return EntityMetaAlreadyHasSingleEntryErrorPayload::class;
    }
}
