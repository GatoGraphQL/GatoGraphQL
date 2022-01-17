<?php

declare(strict_types=1);

namespace PoPWPSchema\TaxonomyMeta\TypeResolvers\InputObjectType;

use PoP\Root\App;
use PoPCMSSchema\TaxonomyMeta\Component;
use PoPCMSSchema\TaxonomyMeta\ComponentConfiguration;
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
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getTaxonomyMetaEntries();
    }
    protected function getAllowOrDenyBehavior(): string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getTaxonomyMetaBehavior();
    }
}
