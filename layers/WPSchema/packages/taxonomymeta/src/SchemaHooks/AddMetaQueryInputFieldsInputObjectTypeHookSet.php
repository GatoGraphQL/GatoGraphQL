<?php

declare(strict_types=1);

namespace PoPWPSchema\TaxonomyMeta\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\AbstractTaxonomiesFilterInputObjectTypeResolver;
use PoPWPSchema\Meta\SchemaHooks\AbstractAddMetaQueryInputFieldsInputObjectTypeHookSet;
use PoPWPSchema\Meta\TypeResolvers\InputObjectType\AbstractMetaQueryInputObjectTypeResolver;
use PoPWPSchema\TaxonomyMeta\TypeResolvers\InputObjectType\TaxonomyMetaQueryInputObjectTypeResolver;

class AddMetaQueryInputFieldsInputObjectTypeHookSet extends AbstractAddMetaQueryInputFieldsInputObjectTypeHookSet
{
    private ?TaxonomyMetaQueryInputObjectTypeResolver $taxonomyMetaQueryInputObjectTypeResolver = null;

    final protected function getTaxonomyMetaQueryInputObjectTypeResolver(): TaxonomyMetaQueryInputObjectTypeResolver
    {
        if ($this->taxonomyMetaQueryInputObjectTypeResolver === null) {
            /** @var TaxonomyMetaQueryInputObjectTypeResolver */
            $taxonomyMetaQueryInputObjectTypeResolver = $this->instanceManager->getInstance(TaxonomyMetaQueryInputObjectTypeResolver::class);
            $this->taxonomyMetaQueryInputObjectTypeResolver = $taxonomyMetaQueryInputObjectTypeResolver;
        }
        return $this->taxonomyMetaQueryInputObjectTypeResolver;
    }

    protected function getMetaQueryInputObjectTypeResolver(): AbstractMetaQueryInputObjectTypeResolver
    {
        return $this->getTaxonomyMetaQueryInputObjectTypeResolver();
    }

    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof AbstractTaxonomiesFilterInputObjectTypeResolver;
    }
}
