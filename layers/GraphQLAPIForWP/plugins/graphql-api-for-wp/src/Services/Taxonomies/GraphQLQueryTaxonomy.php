<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Taxonomies;

class GraphQLQueryTaxonomy extends AbstractTaxonomy
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
    public function getTaxonomyName(bool $uppercase = true): string
    {
        return $uppercase ? \__('Category', 'graphql-api') : \__('category', 'graphql-api');
    }

    /**
     * Taxonomy plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     */
    protected function getTaxonomyPluralNames(bool $uppercase = true): string
    {
        return $uppercase ? \__('Categories', 'graphql-api') : \__('categories', 'graphql-api');
    }
}
