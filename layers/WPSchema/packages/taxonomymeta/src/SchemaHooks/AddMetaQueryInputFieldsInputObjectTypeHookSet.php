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

    final public function setTaxonomyMetaQueryInputObjectTypeResolver(TaxonomyMetaQueryInputObjectTypeResolver $taxonomyMetaQueryInputObjectTypeResolver): void
    {
        $this->taxonomyMetaQueryInputObjectTypeResolver = $taxonomyMetaQueryInputObjectTypeResolver;
    }
    final protected function getTaxonomyMetaQueryInputObjectTypeResolver(): TaxonomyMetaQueryInputObjectTypeResolver
    {
        return $this->taxonomyMetaQueryInputObjectTypeResolver ??= $this->instanceManager->getInstance(TaxonomyMetaQueryInputObjectTypeResolver::class);
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
