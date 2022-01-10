<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\Hooks;

use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\State\ApplicationState;
use PoP\BasicService\AbstractHookSet;
use PoPSchema\CustomPosts\Routing\RouteNatures;
use PoPSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;

class ModelInstanceHookSet extends AbstractHookSet
{
    public const HOOK_VARY_MODEL_INSTANCE_BY_CATEGORY = __CLASS__ . ':vary-model-instance-by-category';

    private ?PostTypeAPIInterface $postTypeAPI = null;
    private ?PostCategoryTypeAPIInterface $postCategoryTypeAPI = null;

    final public function setPostTypeAPI(PostTypeAPIInterface $postTypeAPI): void
    {
        $this->postTypeAPI = $postTypeAPI;
    }
    final protected function getPostTypeAPI(): PostTypeAPIInterface
    {
        return $this->postTypeAPI ??= $this->instanceManager->getInstance(PostTypeAPIInterface::class);
    }
    final public function setPostCategoryTypeAPI(PostCategoryTypeAPIInterface $postCategoryTypeAPI): void
    {
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
    }
    final protected function getPostCategoryTypeAPI(): PostCategoryTypeAPIInterface
    {
        return $this->postCategoryTypeAPI ??= $this->instanceManager->getInstance(PostCategoryTypeAPIInterface::class);
    }

    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            array($this, 'getModelInstanceComponentsFromVars')
        );
    }

    public function getModelInstanceComponentsFromVars($components)
    {
        $vars = ApplicationState::getVars();
        $nature = \PoP\Root\App::getState('nature');

        // Properties specific to each nature
        if (
            $nature == RouteNatures::CUSTOMPOST
            && \PoP\Root\App::getState(['routing', 'queried-object-post-type']) == $this->getPostTypeAPI()->getPostCustomPostType()
        ) {
            // Single may depend on its post_type and category
            // Post and Event may be different
            // Announcements and Articles (Posts), or Past Event and (Upcoming) Event may be different
            // By default, we check for post type but not for categories
            if (
                $this->getHooksAPI()->applyFilters(
                    self::HOOK_VARY_MODEL_INSTANCE_BY_CATEGORY,
                    false
                )
            ) {
                $postID = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $categories = [];
                foreach ($this->getPostCategoryTypeAPI()->getCustomPostCategories($postID) as $cat) {
                    $categories[] = $this->getPostCategoryTypeAPI()->getCategorySlug($cat) . $this->getPostCategoryTypeAPI()->getCategoryID($cat);
                }
                $components[] = $this->__('categories:', 'post-categories') . implode('.', $categories);
            }
        }
        return $components;
    }
}
