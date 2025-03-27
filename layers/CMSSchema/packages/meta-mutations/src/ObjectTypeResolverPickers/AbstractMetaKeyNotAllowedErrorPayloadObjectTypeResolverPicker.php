<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MetaMutations\ObjectModels\MetaKeyNotAllowedErrorPayload;
use PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType\MetaKeyNotAllowedErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractMetaKeyNotAllowedErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?MetaKeyNotAllowedErrorPayloadObjectTypeResolver $taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = null;

    final protected function getMetaKeyNotAllowedErrorPayloadObjectTypeResolver(): MetaKeyNotAllowedErrorPayloadObjectTypeResolver
    {
        if ($this->taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver === null) {
            /** @var MetaKeyNotAllowedErrorPayloadObjectTypeResolver */
            $taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(MetaKeyNotAllowedErrorPayloadObjectTypeResolver::class);
            $this->taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = $taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
        }
        return $this->taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getMetaKeyNotAllowedErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return MetaKeyNotAllowedErrorPayload::class;
    }
}
