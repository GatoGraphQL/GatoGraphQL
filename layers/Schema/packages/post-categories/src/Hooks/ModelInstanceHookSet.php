<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\Hooks;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPosts\Routing\RouteNatures;
use PoPSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;

class ModelInstanceHookSet extends AbstractHookSet
{
    public const HOOK_VARY_MODEL_INSTANCE_BY_CATEGORY = __CLASS__ . ':vary-model-instance-by-category';
    protected PostTypeAPIInterface $postTypeAPI;
    protected PostCategoryTypeAPIInterface $postCategoryTypeAPI;

    #[Required]
    public function autowireModelInstanceHookSet(
        PostTypeAPIInterface $postTypeAPI,
        PostCategoryTypeAPIInterface $postCategoryTypeAPI,
    ): void {
        $this->postTypeAPI = $postTypeAPI;
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
    }

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

        // Properties specific to each nature
        if (
            $nature == RouteNatures::CUSTOMPOST
            && $vars['routing-state']['queried-object-post-type'] == $this->postTypeAPI->getPostCustomPostType()
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
                $categories = [];
                foreach ($this->postCategoryTypeAPI->getCustomPostCategories($postID) as $cat) {
                    $categories[] = $this->postCategoryTypeAPI->getCategorySlug($cat) . $this->postCategoryTypeAPI->getCategoryID($cat);
                }
                $components[] = $this->translationAPI->__('categories:', 'post-categories') . implode('.', $categories);
            }
        }
        return $components;
    }
}
