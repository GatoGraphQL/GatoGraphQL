<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MetaMutations\ObjectModels\EntityMetaKeyWithValueDoesNotExistErrorPayload;
use PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType\EntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractEntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?EntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver $entityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver = null;

    final protected function getEntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver(): EntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->entityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver === null) {
            /** @var EntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver */
            $entityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(EntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->entityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver = $entityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver;
        }
        return $this->entityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver;
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
