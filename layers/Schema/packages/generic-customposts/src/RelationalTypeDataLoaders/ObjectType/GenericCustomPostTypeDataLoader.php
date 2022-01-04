<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts\RelationalTypeDataLoaders\ObjectType;

use PoPSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\AbstractCustomPostTypeDataLoader;
use PoPSchema\GenericCustomPosts\Component;
use PoPSchema\GenericCustomPosts\ComponentConfiguration;

class GenericCustomPostTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    /**
     * Override the custompost-types from the parent
     */
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Root\Managers\ComponentManager::getComponent(Component::class)->getConfiguration();
        return array_merge(
            parent::getQueryToRetrieveObjectsForIDs($ids),
            [
                'custompost-types' => $componentConfiguration->getGenericCustomPostTypes(),
            ]
        );
    }
}
