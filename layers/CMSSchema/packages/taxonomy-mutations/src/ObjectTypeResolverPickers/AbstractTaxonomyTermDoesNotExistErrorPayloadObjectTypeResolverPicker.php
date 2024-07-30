<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TaxonomyMutations\ObjectModels\TaxonomyTermDoesNotExistErrorPayload;
use PoPCMSSchema\TaxonomyMutations\TypeResolvers\ObjectType\TaxonomyTermDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractTaxonomyTermDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?TaxonomyTermDoesNotExistErrorPayloadObjectTypeResolver $categoryDoesNotExistErrorPayloadObjectTypeResolver = null;

    final public function setTaxonomyTermDoesNotExistErrorPayloadObjectTypeResolver(TaxonomyTermDoesNotExistErrorPayloadObjectTypeResolver $categoryDoesNotExistErrorPayloadObjectTypeResolver): void
    {
        $this->categoryDoesNotExistErrorPayloadObjectTypeResolver = $categoryDoesNotExistErrorPayloadObjectTypeResolver;
    }
    final protected function getTaxonomyTermDoesNotExistErrorPayloadObjectTypeResolver(): TaxonomyTermDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->categoryDoesNotExistErrorPayloadObjectTypeResolver === null) {
            /** @var TaxonomyTermDoesNotExistErrorPayloadObjectTypeResolver */
            $categoryDoesNotExistErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(TaxonomyTermDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->categoryDoesNotExistErrorPayloadObjectTypeResolver = $categoryDoesNotExistErrorPayloadObjectTypeResolver;
        }
        return $this->categoryDoesNotExistErrorPayloadObjectTypeResolver;
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
