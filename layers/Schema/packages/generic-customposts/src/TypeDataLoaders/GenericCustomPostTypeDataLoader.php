<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts\TypeDataLoaders;

use PoPSchema\GenericCustomPosts\ComponentConfiguration;
use PoPSchema\CustomPosts\TypeDataLoaders\AbstractCustomPostTypeDataLoader;
use PoPSchema\GenericCustomPosts\ModuleProcessors\GenericCustomPostRelationalFieldDataloadModuleProcessor;

class GenericCustomPostTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    /**
     * Override the custompost-types from the parent
     *
     * @param array $ids
     * @return array
     */
    public function getObjectQuery(array $ids): array
    {
        return array_merge(
            parent::getObjectQuery($ids),
            [
                'custompost-types' => ComponentConfiguration::getGenericCustomPostTypes(),
            ]
        );
    }
    public function getFilterDataloadingModule(): ?array
    {
        return [
            GenericCustomPostRelationalFieldDataloadModuleProcessor::class,
            GenericCustomPostRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_GENERICCUSTOMPOSTLIST
        ];
    }
}
