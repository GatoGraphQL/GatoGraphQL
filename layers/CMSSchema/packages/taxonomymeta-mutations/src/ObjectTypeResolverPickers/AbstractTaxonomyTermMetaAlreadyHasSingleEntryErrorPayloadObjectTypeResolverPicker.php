<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TaxonomyMetaMutations\ObjectModels\TaxonomyTermMetaAlreadyHasSingleEntryErrorPayload;
use PoPCMSSchema\TaxonomyMetaMutations\TypeResolvers\ObjectType\TaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
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
