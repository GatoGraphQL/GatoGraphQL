<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoriesWP\TypeAPIs;

use PoPCMSSchema\CategoriesWP\TypeAPIs\AbstractCategoryTypeAPI;
use PoPCMSSchema\Categories\Module;
use PoPCMSSchema\Categories\ModuleConfiguration;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
use PoP\ComponentModel\App;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostCategoryTypeAPI extends AbstractCategoryTypeAPI implements PostCategoryTypeAPIInterface
{
    /** @var string[] */
    protected ?array $registeredPostCategoryTaxonomyNames = null;

    private ?TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI = null;
    private ?PostTypeAPIInterface $postTypeAPI = null;

    final protected function getTaxonomyTermTypeAPI(): TaxonomyTermTypeAPIInterface
    {
        if ($this->taxonomyTermTypeAPI === null) {
            /** @var TaxonomyTermTypeAPIInterface */
            $taxonomyTermTypeAPI = $this->instanceManager->getInstance(TaxonomyTermTypeAPIInterface::class);
            $this->taxonomyTermTypeAPI = $taxonomyTermTypeAPI;
        }
        return $this->taxonomyTermTypeAPI;
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

    /**
     * The taxonomy name representing a post category ("category")
     */
    public function getPostCategoryTaxonomyName(): string
    {
        return 'category';
    }

    /**
     * @return string[]
     */
    protected function getCategoryTaxonomyNames(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $queryableCategoryTaxonomies = $moduleConfiguration->getQueryableCategoryTaxonomies();

        return array_values(array_intersect(
            $this->getRegisteredPostCategoryTaxonomyNames(),
            $queryableCategoryTaxonomies
        ));
    }

    /**
     * Return all the category taxonomies registered for the "post"
     * custom post type, that have also been selected as "queryable"
     * in the plugin settings.
     *
     * @return string[]
     */
    public function getRegisteredPostCategoryTaxonomyNames(): array
    {
        if ($this->registeredPostCategoryTaxonomyNames === null) {
            $customPostType = $this->getPostTypeAPI()->getPostCustomPostType();
            $taxonomyTermTypeAPI = $this->getTaxonomyTermTypeAPI();
            $customPostTypeTaxonomyNames = $taxonomyTermTypeAPI->getCustomPostTypeTaxonomyNames($customPostType);
            $this->registeredPostCategoryTaxonomyNames = array_values(array_filter(
                $customPostTypeTaxonomyNames,
                fn (string $taxonomyName) => $taxonomyTermTypeAPI->isTaxonomyHierarchical($taxonomyName)
            ));
        }
        return $this->registeredPostCategoryTaxonomyNames;
    }
}
