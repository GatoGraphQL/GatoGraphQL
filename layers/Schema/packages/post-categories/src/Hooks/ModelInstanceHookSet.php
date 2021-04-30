<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\Hooks;

use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPosts\Routing\RouteNatures;
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;

class ModelInstanceHookSet extends AbstractHookSet
{
    public const HOOK_VARY_MODEL_INSTANCE_BY_CATEGORY = __CLASS__ . ':vary-model-instance-by-category';

    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            array($this, 'getModelInstanceComponentsFromVars')
        );
    }

    public function getModelInstanceComponentsFromVars($components)
    {
        $vars = ApplicationState::getVars();
        $nature = $vars['nature'];

        $postTypeAPI = PostTypeAPIFacade::getInstance();

        // Properties specific to each nature
        if (
            $nature == RouteNatures::CUSTOMPOST
            && $vars['routing-state']['queried-object-post-type'] == $postTypeAPI->getPostCustomPostType()
        ) {
            // Single may depend on its post_type and category
            // Post and Event may be different
            // Announcements and Articles (Posts), or Past Event and (Upcoming) Event may be different
            // By default, we check for post type but not for categories
            if (
                $this->hooksAPI->applyFilters(
                    self::HOOK_VARY_MODEL_INSTANCE_BY_CATEGORY,
                    false
                )
            ) {
                $postID = $vars['routing-state']['queried-object-id'];
                $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
                $categories = [];
                foreach ($postCategoryTypeAPI->getCustomPostCategories($postID) as $cat) {
                    $categories[] = $postCategoryTypeAPI->getCategorySlug($cat) . $postCategoryTypeAPI->getCategoryID($cat);
                }
                $components[] = $this->translationAPI->__('categories:', 'post-categories') . implode('.', $categories);
            }
        }
        return $components;
    }
}
