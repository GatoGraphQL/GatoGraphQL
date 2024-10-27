<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TaxonomyMutations\ObjectModels\TaxonomyDoesNotExistErrorPayload;
use PoPCMSSchema\TaxonomyMutations\TypeResolvers\ObjectType\TaxonomyDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractTaxonomyDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?TaxonomyDoesNotExistErrorPayloadObjectTypeResolver $taxonomyDoesNotExistErrorPayloadObjectTypeResolver = null;

    final protected function getTaxonomyDoesNotExistErrorPayloadObjectTypeResolver(): TaxonomyDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->taxonomyDoesNotExistErrorPayloadObjectTypeResolver === null) {
            /** @var TaxonomyDoesNotExistErrorPayloadObjectTypeResolver */
            $taxonomyDoesNotExistErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(TaxonomyDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->taxonomyDoesNotExistErrorPayloadObjectTypeResolver = $taxonomyDoesNotExistErrorPayloadObjectTypeResolver;
        }
        return $this->taxonomyDoesNotExistErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getTaxonomyDoesNotExistErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return TaxonomyDoesNotExistErrorPayload::class;
    }
}
