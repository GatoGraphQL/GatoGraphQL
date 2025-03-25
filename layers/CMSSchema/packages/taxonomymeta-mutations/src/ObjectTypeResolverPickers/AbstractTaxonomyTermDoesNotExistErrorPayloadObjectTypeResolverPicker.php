<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TaxonomyMetaMutations\ObjectModels\TaxonomyTermDoesNotExistErrorPayload;
use PoPCMSSchema\TaxonomyMetaMutations\TypeResolvers\ObjectType\TaxonomyTermDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractTaxonomyTermDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?TaxonomyTermDoesNotExistErrorPayloadObjectTypeResolver $taxonomyTermDoesNotExistErrorPayloadObjectTypeResolver = null;

    final protected function getTaxonomyTermDoesNotExistErrorPayloadObjectTypeResolver(): TaxonomyTermDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->taxonomyTermDoesNotExistErrorPayloadObjectTypeResolver === null) {
            /** @var TaxonomyTermDoesNotExistErrorPayloadObjectTypeResolver */
            $taxonomyTermDoesNotExistErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(TaxonomyTermDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->taxonomyTermDoesNotExistErrorPayloadObjectTypeResolver = $taxonomyTermDoesNotExistErrorPayloadObjectTypeResolver;
        }
        return $this->taxonomyTermDoesNotExistErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getTaxonomyTermDoesNotExistErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return TaxonomyTermDoesNotExistErrorPayload::class;
    }
}
