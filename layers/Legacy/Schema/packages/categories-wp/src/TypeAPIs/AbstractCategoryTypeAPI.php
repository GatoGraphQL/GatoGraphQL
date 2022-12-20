<?php

declare(strict_types=1);

namespace EverythingElse\PoPCMSSchema\CategoriesWP\TypeAPIs;

use EverythingElse\PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\CategoriesWP\TypeAPIs\AbstractCategoryTypeAPI as UpstreamAbstractCategoryTypeAPI;
use PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface;

use function wp_set_post_terms;

abstract class AbstractCategoryTypeAPI extends UpstreamAbstractCategoryTypeAPI implements CategoryTypeAPIInterface
{
    private ?CMSServiceInterface $cmsService = null;

    final public function setCMSService(CMSServiceInterface $cmsService): void
    {
        $this->cmsService = $cmsService;
    }
    final protected function getCMSService(): CMSServiceInterface
    {
        /** @var CMSServiceInterface */
        return $this->cmsService ??= $this->instanceManager->getInstance(CMSServiceInterface::class);
    }

    public function hasCategory($cat_id, $post_id): bool
    {
        return has_category($cat_id, $post_id);
    }

    public function getCategoryPath($category_id): string
    {
        $taxonomy = 'category';

        // Convert it to int, otherwise it thinks it's a string and the method below fails
        $category_path = get_term_link((int) $category_id, $taxonomy);

        // Remove the initial part ("https://www.mesym.com/en/categories/")
        global $wp_rewrite;
        $termlink = $wp_rewrite->get_extra_permastruct($taxonomy);
        $termlink = str_replace("%$taxonomy%", '', $termlink);
        $termlink = home_url(user_trailingslashit($termlink, $taxonomy));

        return substr($category_path, strlen($termlink));
    }

    /**
     * @param mixed[] $categories
     */
    public function setPostCategories(string|int $post_id, array $categories, bool $append = false): void
    {
        wp_set_post_terms($post_id, $categories, $this->getCategoryTaxonomyName(), $append);
    }

    abstract protected function getCategoryBaseOption(): string;

    public function getCategoryBase(): string
    {
        return $this->getCMSService()->getOption($this->getCategoryBaseOption());
    }
}
