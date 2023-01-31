<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\WPDataModel;

use GraphQLAPI\GraphQLAPI\Constants\HookNames;
use GraphQLAPI\GraphQLAPI\Registries\CustomPostTypeRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\CustomPostTypeInterface;
use PoP\Root\Services\BasicServiceTrait;

class WPDataModelProvider implements WPDataModelProviderInterface
{
    use BasicServiceTrait;

    private ?CustomPostTypeRegistryInterface $customPostTypeRegistry = null;

    final public function setCustomPostTypeRegistry(CustomPostTypeRegistryInterface $customPostTypeRegistry): void
    {
        $this->customPostTypeRegistry = $customPostTypeRegistry;
    }
    final protected function getCustomPostTypeRegistry(): CustomPostTypeRegistryInterface
    {
        /** @var CustomPostTypeRegistryInterface */
        return $this->customPostTypeRegistry ??= $this->instanceManager->getInstance(CustomPostTypeRegistryInterface::class);
    }

    /**
     * @return string[]
     */
    public function getFilteredNonGraphQLAPIPluginCustomPostTypes(): array
    {
        // Get the list of custom post types from the system
        $possibleCustomPostTypes = \get_post_types();
        /**
         * Not all custom post types make sense or are allowed.
         * Remove the ones that do not
         */
        $pluginCustomPostTypes = array_map(
            fn (CustomPostTypeInterface $customPostType) => $customPostType->getCustomPostType(),
            $this->getCustomPostTypeRegistry()->getCustomPostTypes()
        );
        $rejectedQueryableCustomPostTypes = \apply_filters(
            HookNames::HOOK_REJECTED_QUERYABLE_CUSTOMPOST_TYPES,
            array_merge(
                /**
                 * Post Types from GraphQL API are just for configuration
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
            HookNames::HOOK_QUERYABLE_CUSTOMPOST_TYPES,
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
}
