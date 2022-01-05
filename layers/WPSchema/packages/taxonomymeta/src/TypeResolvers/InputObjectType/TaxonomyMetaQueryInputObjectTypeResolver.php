<?php

declare(strict_types=1);

namespace PoPWPSchema\TaxonomyMeta\TypeResolvers\InputObjectType;

use PoP\Engine\App;
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
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getTaxonomyMetaEntries();
    }
    protected function getAllowOrDenyBehavior(): string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getTaxonomyMetaBehavior();
    }
}
