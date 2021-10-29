<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\Hooks;

use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPosts\Routing\RouteNatures;
use PoPSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class ModelInstanceHookSet extends AbstractHookSet
{
    public const HOOK_VARY_MODEL_INSTANCE_BY_CATEGORY = __CLASS__ . ':vary-model-instance-by-category';

    protected ?PostTypeAPIInterface $postTypeAPI = null;
    protected ?PostCategoryTypeAPIInterface $postCategoryTypeAPI = null;

    #[Required]
    final public function autowireModelInstanceHookSet(
        PostTypeAPIInterface $postTypeAPI,
        PostCategoryTypeAPIInterface $postCategoryTypeAPI,
    ): void {
        $this->postTypeAPI = $postTypeAPI;
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
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
        $nature = $vars['nature'];

        // Properties specific to each nature
        if (
            $nature == RouteNatures::CUSTOMPOST
            && $vars['routing-state']['queried-object-post-type'] == $this->getPostTypeAPI()->getPostCustomPostType()
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
                $postID = $vars['routing-state']['queried-object-id'];
                $categories = [];
                foreach ($this->getPostCategoryTypeAPI()->getCustomPostCategories($postID) as $cat) {
                    $categories[] = $this->getPostCategoryTypeAPI()->getCategorySlug($cat) . $this->getPostCategoryTypeAPI()->getCategoryID($cat);
                }
                $components[] = $this->getTranslationAPI()->__('categories:', 'post-categories') . implode('.', $categories);
            }
        }
        return $components;
    }
}
