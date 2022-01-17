<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostsWP\Overrides\RelationalTypeDataLoaders\UnionType;

use PoPSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\CustomPostTypeDataLoader;
use PoPSchema\CustomPosts\RelationalTypeDataLoaders\UnionType\CustomPostUnionTypeDataLoader as UpstreamCustomPostUnionTypeDataLoader;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPostsWP\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface;

/**
 * In the context of WordPress, "Custom Posts" are all posts (eg: posts, pages, attachments, events, etc)
 * Hence, this class can simply inherit from the Post dataloader, and add the post-types for all required types
 */
class CustomPostUnionTypeDataLoader extends UpstreamCustomPostUnionTypeDataLoader
{
    private ?CustomPostTypeDataLoader $customPostTypeDataLoader = null;
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;

    final public function setCustomPostTypeDataLoader(CustomPostTypeDataLoader $customPostTypeDataLoader): void
    {
        $this->customPostTypeDataLoader = $customPostTypeDataLoader;
    }
    final protected function getCustomPostTypeDataLoader(): CustomPostTypeDataLoader
    {
        return $this->customPostTypeDataLoader ??= $this->instanceManager->getInstance(CustomPostTypeDataLoader::class);
    }
    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        return $this->customPostTypeAPI ??= $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }

    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        $query = $this->getCustomPostTypeDataLoader()->getQueryToRetrieveObjectsForIDs($ids);

        // From all post types from the member typeResolvers
        $query['custompost-types'] = CustomPostUnionTypeHelpers::getTargetObjectTypeResolverCustomPostTypes();

        return $query;
    }


    public function getUpstreamObjects(array $ids): array
    {
        $query = $this->getQueryToRetrieveObjectsForIDs($ids);
        return $this->getCustomPostTypeDataLoader()->executeQuery($query);
    }

    public function getObjects(array $ids): array
    {
        $customPosts = $this->getUpstreamObjects($ids);

        // After executing `get_posts` it returns a list of custom posts of class WP_Post,
        // without converting the object to its own post type (eg: EM_Event for an "event" custom post type)
        // Cast the custom posts to their own classes
        // Group all the customPosts by targetResolverPicker,
        // so that their casting can be executed in a single query per type
        $customPostTypeTypeResolverPickers = [];
        $customPostTypeItemCustomPosts = [];
        foreach ($customPosts as $customPost) {
            $targetTypeResolverPicker = $this->getCustomPostUnionTypeResolver()->getTargetObjectTypeResolverPicker($customPost);
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
            $customPostType = $targetCustomPostTypeResolverPicker->getCustomPostType();
            $customPostID = $this->getCustomPostTypeAPI()->getID($customPost);
            $customPostTypeTypeResolverPickers[$customPostType] = $targetCustomPostTypeResolverPicker;
            $customPostTypeItemCustomPosts[$customPostType][$customPostID] = $customPost;
        }
        // Cast all objects from the same type in a single query
        $castedCustomPosts = [];
        foreach ($customPostTypeItemCustomPosts as $customPostType => $customPostIDObjects) {
            $customPostTypeTypeResolverPicker = $customPostTypeTypeResolverPickers[$customPostType];
            $castedCustomPosts[$customPostType] = $customPostTypeTypeResolverPicker->maybeCastCustomPosts($customPostIDObjects);
        }

        // Replace each custom post with its casted object
        $customPosts = array_map(
            function (object $customPost) use ($castedCustomPosts) {
                $targetTypeResolverPicker = $this->getCustomPostUnionTypeResolver()->getTargetObjectTypeResolverPicker($customPost);
                if (
                    is_null($targetTypeResolverPicker)
                    || !($targetTypeResolverPicker instanceof CustomPostObjectTypeResolverPickerInterface)
                ) {
                    return $customPost;
                }
                $customPostType = $targetTypeResolverPicker->getCustomPostType();
                $customPostID = $this->getCustomPostTypeAPI()->getID($customPost);
                return $castedCustomPosts[$customPostType][$customPostID];
            },
            $customPosts
        );
        return $customPosts;
    }
}
