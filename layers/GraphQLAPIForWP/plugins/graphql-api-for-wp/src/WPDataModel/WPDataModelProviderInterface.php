<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\WPDataModel;

use WP_Taxonomy;

interface WPDataModelProviderInterface
{
    /**
     * @return string[]
     */
    public function getFilteredNonGraphQLAPIPluginCustomPostTypes(): array;
    /**
     * @return string[]
     */
    public function getFilteredNonGraphQLAPIPluginTagTaxonomies(): array;
    /**
     * @return string[]
     */
    public function getFilteredNonGraphQLAPIPluginCategoryTaxonomies(): array;
    /**
     * Retrieve the taxonomies which are associated to custom posts
     * which have been enabled as queryable.
     *
     * Please notice all entries in "object_type" must be in the whitelist.
     *
     * @return array<string,WP_Taxonomy> Taxonomy name => taxonomy object
     */
    public function getQueryableCustomPostsAssociatedTaxonomies(bool $isHierarchical): array;
}
