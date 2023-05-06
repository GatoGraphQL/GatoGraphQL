<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\WPDataModel;

use WP_Taxonomy;

interface WPDataModelProviderInterface
{
    /**
     * @param array<string,mixed> $customPostTypeArgs
     * @return string[]
     */
    public function getFilteredNonGatoGraphQLPluginCustomPostTypes(array $customPostTypeArgs = []): array;
    /**
     * @return string[]
     */
    public function getFilteredNonGatoGraphQLPluginTagTaxonomies(): array;
    /**
     * @return string[]
     */
    public function getFilteredNonGatoGraphQLPluginCategoryTaxonomies(): array;
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
