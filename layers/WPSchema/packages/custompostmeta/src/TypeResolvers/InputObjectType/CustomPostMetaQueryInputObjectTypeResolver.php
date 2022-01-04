<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPostMeta\TypeResolvers\InputObjectType;

use PoPSchema\CustomPostMeta\Component;
use PoPSchema\CustomPostMeta\ComponentConfiguration;
use PoPWPSchema\Meta\TypeResolvers\InputObjectType\AbstractMetaQueryInputObjectTypeResolver;

class CustomPostMetaQueryInputObjectTypeResolver extends AbstractMetaQueryInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostMetaQueryInput';
    }

    /**
     * @return string[]
     */
    protected function getAllowOrDenyEntries(): array
    {
        return ComponentConfiguration::getCustomPostMetaEntries();
    }
    protected function getAllowOrDenyBehavior(): string
    {
        return ComponentConfiguration::getCustomPostMetaBehavior();
    }
}
