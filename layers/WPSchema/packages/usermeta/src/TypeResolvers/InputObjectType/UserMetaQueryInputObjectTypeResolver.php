<?php

declare(strict_types=1);

namespace PoPWPSchema\UserMeta\TypeResolvers\InputObjectType;

use PoPSchema\UserMeta\Component;
use PoPSchema\UserMeta\ComponentConfiguration;
use PoPWPSchema\Meta\TypeResolvers\InputObjectType\AbstractMetaQueryInputObjectTypeResolver;

class UserMetaQueryInputObjectTypeResolver extends AbstractMetaQueryInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'UserMetaQueryInput';
    }

    /**
     * @return string[]
     */
    protected function getAllowOrDenyEntries(): array
    {
        return ComponentConfiguration::getUserMetaEntries();
    }
    protected function getAllowOrDenyBehavior(): string
    {
        return ComponentConfiguration::getUserMetaBehavior();
    }
}
