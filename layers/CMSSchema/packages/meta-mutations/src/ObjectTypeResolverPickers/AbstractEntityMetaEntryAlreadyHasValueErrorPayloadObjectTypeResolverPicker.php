<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MetaMutations\ObjectModels\EntityMetaEntryAlreadyHasValueErrorPayload;
use PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType\EntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractEntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?EntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolver $entityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolver = null;

    final protected function getEntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolver(): EntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolver
    {
        if ($this->entityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolver === null) {
            /** @var EntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolver */
            $entityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(EntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolver::class);
            $this->entityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolver = $entityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolver;
        }
        return $this->entityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getEntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return EntityMetaEntryAlreadyHasValueErrorPayload::class;
    }
}
