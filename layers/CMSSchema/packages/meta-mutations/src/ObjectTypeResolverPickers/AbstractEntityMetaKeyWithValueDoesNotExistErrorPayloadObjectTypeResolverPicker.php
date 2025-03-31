<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MetaMutations\ObjectModels\EntityMetaKeyWithValueDoesNotExistErrorPayload;
use PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType\EntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractEntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?EntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver $entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = null;

    final protected function getEntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver(): EntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver === null) {
            /** @var EntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver */
            $entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(EntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = $entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
        }
        return $this->entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getEntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return EntityMetaKeyWithValueDoesNotExistErrorPayload::class;
    }
}
