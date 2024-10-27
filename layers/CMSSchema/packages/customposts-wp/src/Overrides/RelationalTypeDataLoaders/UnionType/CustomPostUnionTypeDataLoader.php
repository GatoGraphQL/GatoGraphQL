<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostsWP\Overrides\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostsWP\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoPCMSSchema\CustomPosts\ModuleConfiguration as CustomPostsModuleConfiguration;
use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\CustomPostObjectTypeDataLoader;
use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\UnionType\CustomPostUnionTypeDataLoader as UpstreamCustomPostUnionTypeDataLoader;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoP\ComponentModel\App;
use SplObjectStorage;

/**
 * In the context of WordPress, "Custom Posts" are all posts (eg: posts, pages, attachments, events, etc)
 * Hence, this class can simply inherit from the Post dataloader, and add the post-types for all required types
 */
class CustomPostUnionTypeDataLoader extends UpstreamCustomPostUnionTypeDataLoader
{
    public const HOOK_ALL_OBJECTS_BY_IDS_QUERY = __CLASS__ . ':all-objects-by-ids-query';

    private ?CustomPostObjectTypeDataLoader $customPostObjectTypeDataLoader = null;
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;

    final protected function getCustomPostObjectTypeDataLoader(): CustomPostObjectTypeDataLoader
    {
        if ($this->customPostObjectTypeDataLoader === null) {
            /** @var CustomPostObjectTypeDataLoader */
            $customPostObjectTypeDataLoader = $this->instanceManager->getInstance(CustomPostObjectTypeDataLoader::class);
            $this->customPostObjectTypeDataLoader = $customPostObjectTypeDataLoader;
        }
        return $this->customPostObjectTypeDataLoader;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        if ($this->customPostTypeAPI === null) {
            /** @var CustomPostTypeAPIInterface */
            $customPostTypeAPI = $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
            $this->customPostTypeAPI = $customPostTypeAPI;
        }
        return $this->customPostTypeAPI;
    }

    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        $query = $this->getCustomPostObjectTypeDataLoader()->getQueryToRetrieveObjectsForIDs($ids);

        // From all post types from the member typeResolvers
        /** @var CustomPostsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostsModule::class)->getConfiguration();
        $query['custompost-types'] = $moduleConfiguration->getQueryableCustomPostTypes();

        return App::applyFilters(
            self::HOOK_ALL_OBJECTS_BY_IDS_QUERY,
            $query,
            $ids
        );
    }


    /**
     * @return object[]
     * @param array<string|int> $ids
     */
    protected function getUpstreamObjects(array $ids): array
    {
        $query = $this->getQueryToRetrieveObjectsForIDs($ids);
        return $this->getCustomPostObjectTypeDataLoader()->executeQuery($query);
    }

    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects(array $ids): array
    {
        $customPosts = $this->getUpstreamObjects($ids);

        $customPostUnionTypeResolver = $this->getCustomPostUnionTypeResolver();
        $customPostTypeAPI = $this->getCustomPostTypeAPI();

        /**
         * After executing `get_posts` it returns a list of custom posts
         * of class WP_Post, without converting the object to its own post
         * type (eg: EM_Event for an "event" custom post type).
         * Cast the custom posts to their own classes.
         * Group all the customPosts by targetResolverPicker,
         * so that their casting can be executed in a single query per type
         *
         * @var SplObjectStorage<CustomPostObjectTypeResolverPickerInterface,array<string|int,object>>
         */
        $customPostTypeResolverPickerItemCustomPosts = new SplObjectStorage();
        foreach ($customPosts as $customPost) {
            $targetTypeResolverPicker = $customPostUnionTypeResolver->getTargetObjectTypeResolverPicker($customPost);
            if (
                // If `null`, no picker handles this type, then do nothing
                $targetTypeResolverPicker === null
                // Needs be an instance of this interface, or do nothing
                || !($targetTypeResolverPicker instanceof CustomPostObjectTypeResolverPickerInterface)
            ) {
                continue;
            }
            // Add the Custom Post Type as the key, which can uniquely identify the picker
            /** @var CustomPostObjectTypeResolverPickerInterface */
            $targetCustomPostTypeResolverPicker = $targetTypeResolverPicker;
            $customPostID = $customPostTypeAPI->getID($customPost);
            $customPostTypeItemCustomPosts = $customPostTypeResolverPickerItemCustomPosts[$targetCustomPostTypeResolverPicker] ?? [];
            $customPostTypeItemCustomPosts[$customPostID] = $customPost;
            $customPostTypeResolverPickerItemCustomPosts[$targetCustomPostTypeResolverPicker] = $customPostTypeItemCustomPosts;
        }

        /**
         * Cast all objects from the same type in a single query
         *
         * @var SplObjectStorage<CustomPostObjectTypeResolverPickerInterface,array<string|int,object>>
         */
        $castedCustomPosts = new SplObjectStorage();
        /** @var CustomPostObjectTypeResolverPickerInterface $customPostTypeTypeResolverPicker */
        foreach ($customPostTypeResolverPickerItemCustomPosts as $customPostTypeTypeResolverPicker) {
            /** @var array<string|int,object> */
            $customPostIDObjects = $customPostTypeResolverPickerItemCustomPosts[$customPostTypeTypeResolverPicker];
            $castedCustomPosts[$customPostTypeTypeResolverPicker] = $customPostTypeTypeResolverPicker->maybeCastCustomPosts($customPostIDObjects);
        }

        // Replace each custom post with its casted object
        $customPosts = array_map(
            function (object $customPost) use ($castedCustomPosts, $customPostUnionTypeResolver, $customPostTypeAPI) {
                $targetTypeResolverPicker = $customPostUnionTypeResolver->getTargetObjectTypeResolverPicker($customPost);
                if (
                    $targetTypeResolverPicker === null
                    || !($targetTypeResolverPicker instanceof CustomPostObjectTypeResolverPickerInterface)
                ) {
                    return $customPost;
                }
                $customPostID = $customPostTypeAPI->getID($customPost);
                return $castedCustomPosts[$targetTypeResolverPicker][$customPostID];
            },
            $customPosts
        );
        return $customPosts;
    }
}
