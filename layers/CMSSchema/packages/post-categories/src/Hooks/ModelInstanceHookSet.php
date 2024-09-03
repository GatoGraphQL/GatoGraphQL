<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\Hooks;

use PoPCMSSchema\Categories\TypeAPIs\UniversalCategoryTypeAPIInterface;
use PoPCMSSchema\CustomPosts\Routing\RequestNature;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class ModelInstanceHookSet extends AbstractHookSet
{
    public final const HOOK_VARY_MODEL_INSTANCE_BY_CATEGORY = __CLASS__ . ':vary-model-instance-by-category';

    private ?PostTypeAPIInterface $postTypeAPI = null;
    private ?PostCategoryTypeAPIInterface $postCategoryTypeAPI = null;
    private ?UniversalCategoryTypeAPIInterface $universalCategoryTypeAPI = null;

    final public function setPostTypeAPI(PostTypeAPIInterface $postTypeAPI): void
    {
        $this->postTypeAPI = $postTypeAPI;
    }
    final protected function getPostTypeAPI(): PostTypeAPIInterface
    {
        if ($this->postTypeAPI === null) {
            /** @var PostTypeAPIInterface */
            $postTypeAPI = $this->instanceManager->getInstance(PostTypeAPIInterface::class);
            $this->postTypeAPI = $postTypeAPI;
        }
        return $this->postTypeAPI;
    }
    final public function setPostCategoryTypeAPI(PostCategoryTypeAPIInterface $postCategoryTypeAPI): void
    {
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
    }
    final protected function getPostCategoryTypeAPI(): PostCategoryTypeAPIInterface
    {
        if ($this->postCategoryTypeAPI === null) {
            /** @var PostCategoryTypeAPIInterface */
            $postCategoryTypeAPI = $this->instanceManager->getInstance(PostCategoryTypeAPIInterface::class);
            $this->postCategoryTypeAPI = $postCategoryTypeAPI;
        }
        return $this->postCategoryTypeAPI;
    }
    final public function setUniversalCategoryTypeAPI(UniversalCategoryTypeAPIInterface $universalCategoryTypeAPI): void
    {
        $this->universalCategoryTypeAPI = $universalCategoryTypeAPI;
    }
    final protected function getUniversalCategoryTypeAPI(): UniversalCategoryTypeAPIInterface
    {
        if ($this->universalCategoryTypeAPI === null) {
            /** @var UniversalCategoryTypeAPIInterface */
            $universalCategoryTypeAPI = $this->instanceManager->getInstance(UniversalCategoryTypeAPIInterface::class);
            $this->universalCategoryTypeAPI = $universalCategoryTypeAPI;
        }
        return $this->universalCategoryTypeAPI;
    }

    protected function init(): void
    {
        App::addFilter(
            ModelInstance::HOOK_ELEMENTS_RESULT,
            $this->getModelInstanceElementsFromAppState(...)
        );
    }

    /**
     * @return string[]
     * @param string[] $elements
     */
    public function getModelInstanceElementsFromAppState(array $elements): array
    {
        $nature = App::getState('nature');

        // Properties specific to each nature
        if (
            $nature === RequestNature::CUSTOMPOST
            && App::getState(['routing', 'queried-object-post-type']) === $this->getPostTypeAPI()->getPostCustomPostType()
        ) {
            // Single may depend on its post_type and category
            // Post and Event may be different
            // Announcements and Articles (Posts), or Past Event and (Upcoming) Event may be different
            // By default, we check for post type but not for categories
            if (
                App::applyFilters(
                    self::HOOK_VARY_MODEL_INSTANCE_BY_CATEGORY,
                    false
                )
            ) {
                $postCategoryTypeAPI = $this->getPostCategoryTypeAPI();
                $universalCategoryTypeAPI = $this->getUniversalCategoryTypeAPI();
                $postID = App::getState(['routing', 'queried-object-id']);
                $categories = [];
                foreach ($postCategoryTypeAPI->getCustomPostCategories($postID) as $cat) {
                    $categoryID = is_object($cat) ? $postCategoryTypeAPI->getCategoryID($cat) : $cat;
                    /** @var string */
                    $slug = $universalCategoryTypeAPI->getCategorySlug($cat);
                    $categories[] = $slug . $categoryID;
                }
                $elements[] = $this->__('categories:', 'post-categories') . implode('.', $categories);
            }
        }
        return $elements;
    }
}
