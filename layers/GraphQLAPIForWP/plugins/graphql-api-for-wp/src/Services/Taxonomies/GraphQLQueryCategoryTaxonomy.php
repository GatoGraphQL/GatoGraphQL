<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Taxonomies;

class GraphQLQueryCategoryTaxonomy extends AbstractCategory
{
    public function getTaxonomy(): string
    {
        return 'graphql-category';
    }

    public function getTaxonomyName(bool $titleCase = true): string
    {
        return $titleCase ? \__('Category', 'graphql-api') : \__('category', 'graphql-api');
    }

    /**
     * @param bool $titleCase Indicate if the name must be title case (for starting a sentence) or, otherwise, lowercase
     */
    protected function getTaxonomyPluralNames(bool $titleCase = true): string
    {
        return $titleCase ? \__('Categories', 'graphql-api') : \__('categories', 'graphql-api');
    }
}
