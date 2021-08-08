<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts\TypeDataLoaders;

use PoPSchema\GenericCustomPosts\ComponentConfiguration;
use PoPSchema\CustomPosts\TypeDataLoaders\AbstractCustomPostTypeDataLoader;
use PoPSchema\GenericCustomPosts\ModuleProcessors\GenericCustomPostFilterInnerModuleProcessor;

class GenericCustomPostTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    /**
     * Override the custompost-types from the parent
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
    public function getDataFilteringModule(): ?array
    {
        return [
            GenericCustomPostFilterInnerModuleProcessor::class,
            GenericCustomPostFilterInnerModuleProcessor::MODULE_FILTERINNER_GENERICCUSTOMPOSTLIST
        ];
    }
}
