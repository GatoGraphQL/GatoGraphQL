<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\WPDataModel;

use GatoGraphQL\GatoGraphQL\Constants\HookNames;
use GatoGraphQL\GatoGraphQL\Registries\CustomPostTypeRegistryInterface;
use GatoGraphQL\GatoGraphQL\Registries\TaxonomyRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\CustomPostTypeInterface;
use GatoGraphQL\GatoGraphQL\Services\Taxonomies\TaxonomyInterface;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoPCMSSchema\CustomPosts\ModuleConfiguration as CustomPostsModuleConfiguration;
use PoP\ComponentModel\App;
use PoP\Root\Services\BasicServiceTrait;
use WP_Taxonomy;

class WPDataModelProvider implements WPDataModelProviderInterface
{
    use BasicServiceTrait;

    /** @var array<string,WP_Taxonomy>|null */
    protected ?array $hierarchicalQueryableCustomPostsAssociatedTaxonomies = null;

    /** @var array<string,WP_Taxonomy>|null */
    protected ?array $nonHierarchicalQueryableCustomPostsAssociatedTaxonomies = null;

    private ?CustomPostTypeRegistryInterface $customPostTypeRegistry = null;
    private ?TaxonomyRegistryInterface $taxonomyRegistry = null;

    final protected function getCustomPostTypeRegistry(): CustomPostTypeRegistryInterface
    {
        if ($this->customPostTypeRegistry === null) {
            /** @var CustomPostTypeRegistryInterface */
            $customPostTypeRegistry = $this->instanceManager->getInstance(CustomPostTypeRegistryInterface::class);
            $this->customPostTypeRegistry = $customPostTypeRegistry;
        }
        return $this->customPostTypeRegistry;
    }
    final protected function getTaxonomyRegistry(): TaxonomyRegistryInterface
    {
        if ($this->taxonomyRegistry === null) {
            /** @var TaxonomyRegistryInterface */
            $taxonomyRegistry = $this->instanceManager->getInstance(TaxonomyRegistryInterface::class);
            $this->taxonomyRegistry = $taxonomyRegistry;
        }
        return $this->taxonomyRegistry;
    }

    /**
     * @param array<string,mixed> $customPostTypeArgs
     * @return string[]
     */
    public function getFilteredNonGatoGraphQLPluginCustomPostTypes(array $customPostTypeArgs = []): array
    {
        // Get the list of custom post types from the system
        $possibleCustomPostTypes = \get_post_types($customPostTypeArgs);
        /**
         * Not all custom post types make sense or are allowed.
         * Remove the ones that do not
         */
        $pluginCustomPostTypes = array_map(
            fn (CustomPostTypeInterface $customPostType) => $customPostType->getCustomPostType(),
            $this->getCustomPostTypeRegistry()->getCustomPostTypes()
        );
        $rejectedQueryableCustomPostTypes = \apply_filters(
            HookNames::REJECTED_QUERYABLE_CUSTOMPOST_TYPES,
            array_merge(
                /**
                 * Post Types from Gato GraphQL are just for configuration
                 * and contain private data
                 */
                $pluginCustomPostTypes,
                /**
                 * WordPress internal CPTs.
                 *
                 * Watch out: Attachment has post_status "inherit",
                 * which is by default not included in the "status"
                 * filter, so the query must make it explicit:
                 * `filter: { status: ["inherit"] }`.
                 *
                 * Similar with Revision and status "auto-draft"
                 */
                $this->removeWordPressInternalCustomPostTypes()
                    ? $this->getWordPressInternalCustomPostTypes()
                    : []
            )
        );
        $possibleCustomPostTypes = array_values(array_diff(
            $possibleCustomPostTypes,
            $rejectedQueryableCustomPostTypes
        ));
        // Allow plugins to further remove unwanted custom post types
        $possibleCustomPostTypes = \apply_filters(
            HookNames::QUERYABLE_CUSTOMPOST_TYPES,
            $possibleCustomPostTypes
        );
        sort($possibleCustomPostTypes);

        return $possibleCustomPostTypes;
    }

    protected function removeWordPressInternalCustomPostTypes(): bool
    {
        return false;
    }

    /**
     * @return string[]
     */
    protected function getWordPressInternalCustomPostTypes(): array
    {
        return [
            'attachment',
            'custom_css',
            'customize_changeset',
            'nav_menu_item',
            'oembed_cache',
            'revision',
            'user_request',
            'wp_area',
            'wp_block',
            'wp_global_styles',
            'wp_navigation',
            'wp_template_part',
            'wp_template',
        ];
    }

    /**
     * @param string[]|null $queryableCustomPostTypes
     * @return string[]
     */
    public function getFilteredNonGatoGraphQLPluginTagTaxonomies(?array $queryableCustomPostTypes = null): array
    {
        // Get the list of tag taxonomies from the system
        $queryableTagTaxonomyNameObjects = $this->getQueryableCustomPostsAssociatedTaxonomies(false, $queryableCustomPostTypes);
        /**
         * Possibly not all tag taxonomies must be allowed.
         * Remove the ones that do not
         */
        $pluginTagTaxonomies = array_map(
            fn (TaxonomyInterface $taxonomy) => $taxonomy->getTaxonomy(),
            $this->getTaxonomyRegistry()->getTaxonomies(false)
        );
        $rejectedQueryableTagTaxonomies = \apply_filters(
            HookNames::REJECTED_QUERYABLE_TAG_TAXONOMIES,
            []
        );
        $possibleTagTaxonomies = array_values(array_diff(
            array_keys($queryableTagTaxonomyNameObjects),
            $pluginTagTaxonomies,
            $rejectedQueryableTagTaxonomies
        ));
        // Allow plugins to further remove unwanted tag taxonomies
        $possibleTagTaxonomies = \apply_filters(
            HookNames::QUERYABLE_TAG_TAXONOMIES,
            $possibleTagTaxonomies
        );
        sort($possibleTagTaxonomies);
        return $possibleTagTaxonomies;
    }

    /**
     * @param string[]|null $queryableCustomPostTypes
     * @return string[]
     */
    public function getFilteredNonGatoGraphQLPluginCategoryTaxonomies(?array $queryableCustomPostTypes = null): array
    {
        // Get the list of category taxonomies from the system
        $queryableCategoryTaxonomyNameObjects = $this->getQueryableCustomPostsAssociatedTaxonomies(true, $queryableCustomPostTypes);
        /**
         * Possibly not all category taxonomies must be allowed.
         * Remove the ones that do not
         */
        $pluginCategoryTaxonomies = array_map(
            fn (TaxonomyInterface $taxonomy) => $taxonomy->getTaxonomy(),
            $this->getTaxonomyRegistry()->getTaxonomies(true)
        );
        $rejectedQueryableCategoryTaxonomies = \apply_filters(
            HookNames::REJECTED_QUERYABLE_CATEGORY_TAXONOMIES,
            []
        );
        $possibleCategoryTaxonomies = array_values(array_diff(
            array_keys($queryableCategoryTaxonomyNameObjects),
            $pluginCategoryTaxonomies,
            $rejectedQueryableCategoryTaxonomies
        ));
        // Allow plugins to further remove unwanted category taxonomies
        $possibleCategoryTaxonomies = \apply_filters(
            HookNames::QUERYABLE_CATEGORY_TAXONOMIES,
            $possibleCategoryTaxonomies
        );
        sort($possibleCategoryTaxonomies);
        return $possibleCategoryTaxonomies;
    }

    /**
     * Retrieve the taxonomies which are associated to custom posts
     * which have been enabled as queryable.
     *
     * Please notice all entries in "object_type" must be in the whitelist.
     *
     * @param string[]|null $queryableCustomPostTypes
     * @return array<string,WP_Taxonomy> Taxonomy name => taxonomy object
     */
    public function getQueryableCustomPostsAssociatedTaxonomies(bool $isHierarchical, ?array $queryableCustomPostTypes = null): array
    {
        if ($isHierarchical && $this->hierarchicalQueryableCustomPostsAssociatedTaxonomies !== null) {
            return $this->hierarchicalQueryableCustomPostsAssociatedTaxonomies;
        }
        if (!$isHierarchical && $this->nonHierarchicalQueryableCustomPostsAssociatedTaxonomies !== null) {
            return $this->nonHierarchicalQueryableCustomPostsAssociatedTaxonomies;
        }

        /**
         * When we are saving the Settings on option.php, do not
         * restrict the taxonomies to the previous CPT values stored
         * in the DB. That's why this value can be provided from outside.
         *
         * @see layers/GatoGraphQLForWP/plugins/gatographql/src/ModuleResolvers/SchemaTypeModuleResolver.php method `getSettingsDefaultValue`
         *
         * In particular: when clicking on "Reset Settings" and all CPTs are
         * exposed, all tags/categories must also be exposed, and not those
         * ones associated to the previously-stored CPTs.
         */
        if ($queryableCustomPostTypes === null) {
            /** @var CustomPostsModuleConfiguration */
            $moduleConfiguration = App::getModule(CustomPostsModule::class)->getConfiguration();
            $queryableCustomPostTypes = $moduleConfiguration->getQueryableCustomPostTypes();
        }

        /** @var WP_Taxonomy[] */
        $possibleTaxonomyObjects = \get_taxonomies(
            [
                'hierarchical' => $isHierarchical,
            ],
            'objects'
        );

        $possibleTaxonomyObjects = array_filter(
            $possibleTaxonomyObjects,
            fn (WP_Taxonomy $taxonomy) => array_diff(
                $taxonomy->object_type,
                $queryableCustomPostTypes
            ) === []
        );

        $possibleTaxonomyNameObjects = [];
        foreach ($possibleTaxonomyObjects as $taxonomyObject) {
            $possibleTaxonomyNameObjects[$taxonomyObject->name] = $taxonomyObject;
        }

        if ($isHierarchical) {
            $this->hierarchicalQueryableCustomPostsAssociatedTaxonomies = $possibleTaxonomyNameObjects;
        } else {
            $this->nonHierarchicalQueryableCustomPostsAssociatedTaxonomies = $possibleTaxonomyNameObjects;
        }

        return $possibleTaxonomyNameObjects;
    }
}
