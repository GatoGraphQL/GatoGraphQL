<?php

declare(strict_types=1);

namespace PoPWPSchema\TaxonomyMeta\TypeResolvers\InputObjectType;

use PoP\Root\App;
use PoPCMSSchema\TaxonomyMeta\Module;
use PoPCMSSchema\TaxonomyMeta\ModuleConfiguration;
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
        /** @var ModuleConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        return $componentConfiguration->getTaxonomyMetaEntries();
    }
    protected function getAllowOrDenyBehavior(): string
    {
        /** @var ModuleConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        return $componentConfiguration->getTaxonomyMetaBehavior();
    }
}
