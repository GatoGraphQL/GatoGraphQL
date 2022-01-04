<?php

declare(strict_types=1);

namespace PoPWPSchema\UserMeta\TypeResolvers\InputObjectType;

use PoP\Root\Managers\ComponentManager;
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
        /** @var ComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getUserMetaEntries();
    }
    protected function getAllowOrDenyBehavior(): string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getUserMetaBehavior();
    }
}
