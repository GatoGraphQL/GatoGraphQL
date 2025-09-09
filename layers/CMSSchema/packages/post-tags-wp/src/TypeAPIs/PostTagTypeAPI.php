<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagsWP\TypeAPIs;

use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPCMSSchema\TagsWP\TypeAPIs\AbstractTagTypeAPI;
use PoPCMSSchema\Tags\Module;
use PoPCMSSchema\Tags\ModuleConfiguration;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
use PoP\ComponentModel\App;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostTagTypeAPI extends AbstractTagTypeAPI implements PostTagTypeAPIInterface
{
    /** @var string[] */
    protected ?array $registeredPostTagTaxonomyNames = null;

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
     * The taxonomy name representing a post tag ("post_tag")
     */
    public function getPostTagTaxonomyName(): string
    {
        return 'post_tag';
    }

    /**
     * @return string[]
     */
    protected function getTagTaxonomyNames(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $queryableTagTaxonomies = $moduleConfiguration->getQueryableTagTaxonomies();

        return array_values(array_intersect(
            $this->getRegisteredPostTagTaxonomyNames(),
            $queryableTagTaxonomies
        ));
    }

    /**
     * Return all the tag taxonomies registered for the "post"
     * custom post type, that have also been selected as "queryable"
     * in the plugin settings.
     *
     * @return string[]
     */
    public function getRegisteredPostTagTaxonomyNames(): array
    {
        if ($this->registeredPostTagTaxonomyNames === null) {
            $customPostType = $this->getPostTypeAPI()->getPostCustomPostType();
            $taxonomyTermTypeAPI = $this->getTaxonomyTermTypeAPI();
            $customPostTypeTaxonomyNames = $taxonomyTermTypeAPI->getCustomPostTypeTaxonomyNames($customPostType);
            $this->registeredPostTagTaxonomyNames = array_values(array_filter(
                $customPostTypeTaxonomyNames,
                fn (string $taxonomyName) => !($taxonomyTermTypeAPI->isTaxonomyHierarchical($taxonomyName) ?? false)
            ));
        }
        return $this->registeredPostTagTaxonomyNames;
    }
}
