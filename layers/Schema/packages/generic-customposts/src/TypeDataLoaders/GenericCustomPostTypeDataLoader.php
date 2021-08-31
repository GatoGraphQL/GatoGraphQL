<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts\TypeDataLoaders;

use PoPSchema\GenericCustomPosts\ComponentConfiguration;
use PoPSchema\CustomPosts\TypeDataLoaders\AbstractCustomPostTypeDataLoader;

class GenericCustomPostTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    /**
     * Override the custompost-types from the parent
     */
    public function getDataFromIdsQuery(array $ids): array
    {
        return array_merge(
            parent::getDataFromIdsQuery($ids),
            [
                'custompost-types' => ComponentConfiguration::getGenericCustomPostTypes(),
            ]
        );
    }
}
