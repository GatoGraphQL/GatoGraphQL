<?php

declare(strict_types=1);

namespace PoPWPSchema\CommentMeta\TypeResolvers\InputObjectType;

use PoP\Root\Managers\ComponentManager;
use PoPSchema\CommentMeta\Component;
use PoPSchema\CommentMeta\ComponentConfiguration;
use PoPWPSchema\Meta\TypeResolvers\InputObjectType\AbstractMetaQueryInputObjectTypeResolver;

class CommentMetaQueryInputObjectTypeResolver extends AbstractMetaQueryInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CommentMetaQueryInput';
    }

    /**
     * @return string[]
     */
    protected function getAllowOrDenyEntries(): array
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getCommentMetaEntries();
    }
    protected function getAllowOrDenyBehavior(): string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getCommentMetaBehavior();
    }
}
