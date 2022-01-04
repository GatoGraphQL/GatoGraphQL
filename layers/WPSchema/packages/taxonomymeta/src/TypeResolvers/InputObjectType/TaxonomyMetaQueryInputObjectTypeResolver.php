<?php

declare(strict_types=1);

namespace PoPWPSchema\TaxonomyMeta\TypeResolvers\InputObjectType;

use PoPSchema\TaxonomyMeta\Component;
use PoPSchema\TaxonomyMeta\ComponentConfiguration;
use PoPWPSchema\Meta\TypeResolvers\InputObjectType\AbstractMetaQueryInputObjectTypeResolver;

class TaxonomyMetaQueryInputObjectTypeResolver extends AbstractMetaQueryInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'TaxonomyMetaQueryInput';
    }

    /**
     * @return string[]
     */
    protected function getAllowOrDenyEntries(): array
    {
        return ComponentConfiguration::getTaxonomyMetaEntries();
    }
    protected function getAllowOrDenyBehavior(): string
    {
        return ComponentConfiguration::getTaxonomyMetaBehavior();
    }
}
