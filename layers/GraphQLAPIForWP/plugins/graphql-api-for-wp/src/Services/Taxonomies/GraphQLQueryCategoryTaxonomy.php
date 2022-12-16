<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Taxonomies;

class GraphQLQueryCategoryTaxonomy extends AbstractCategory
{
    /**
     * "Category" taxonomy
     */
    public final const TAXONOMY_CATEGORY = 'graphql-category';

    /**
     * Taxonomy
     */
    public function getTaxonomy(): string
    {
        return self::TAXONOMY_CATEGORY;
    }

    /**
     * Taxonomy name
     */
    public function getTaxonomyName(bool $titleCase = true): string
    {
        return $titleCase ? \__('Category', 'graphql-api') : \__('category', 'graphql-api');
    }

    /**
     * Taxonomy plural name
     *
     * @param bool $titleCase Indicate if the name must be title case (for starting a sentence) or, otherwise, lowercase
     */
    protected function getTaxonomyPluralNames(bool $titleCase = true): string
    {
        return $titleCase ? \__('Categories', 'graphql-api') : \__('categories', 'graphql-api');
    }
}
