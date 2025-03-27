<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MetaMutations\ObjectModels\TaxonomyTermMetaAlreadyHasSingleEntryErrorPayload;
use PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType\TaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractTaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?TaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver $taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = null;

    final protected function getTaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver(): TaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver
    {
        if ($this->taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver === null) {
            /** @var TaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver */
            $taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(TaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver::class);
            $this->taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = $taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
        }
        return $this->taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getTaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return TaxonomyTermMetaAlreadyHasSingleEntryErrorPayload::class;
    }
}
