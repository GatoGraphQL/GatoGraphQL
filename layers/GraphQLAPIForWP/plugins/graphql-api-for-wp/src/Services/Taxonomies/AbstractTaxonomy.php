<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Taxonomies;

use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractTaxonomy extends AbstractAutomaticallyInstantiatedService
{
    /**
     * Taxonomy
     */
    abstract public function getTaxonomy(): string;

    /**
     * Add the hook to initialize the different taxonomies
     */
    final public function initialize(): void
    {
        \add_action(
            'init',
            $this->initTaxonomy(...)
        );
    }

    /**
     * Labels for registering the taxonomy
     *
     * @param string $name_uc Singular name uppercase
     * @param string $names_uc Plural name uppercase
     * @param string $name_lc Singulare name lowercase
     * @param string $names_lc Plural name lowercase
     * @return array<string, string>
     */
    protected function getTaxonomyLabels(string $name_uc, string $names_uc, string $name_lc, string $names_lc): array
    {
        return array(
            'name'                           => $names_uc,
            'singular_name'                  => $name_uc,
            'menu_name'                      => $names_uc,
            'search_items'                   => \sprintf(\__('Search %s', 'graphql-api'), $names_uc),
            'all_items'                      => $names_uc,//\sprintf(\__('All %s', 'graphql-api'), $names_uc),
            'edit_item'                      => \sprintf(\__('Edit %s', 'graphql-api'), $name_uc),
            'update_item'                    => \sprintf(\__('Update %s', 'graphql-api'), $name_uc),
            'add_new_item'                   => \sprintf(\__('Add New %s', 'graphql-api'), $name_uc),
            'new_item_name'                  => \sprintf(\__('Add New %s', 'graphql-api'), $name_uc),
            'view_item'                      => \sprintf(\__('View %s', 'graphql-api'), $name_uc),
            'popular_items'                  => \sprintf(\__('Popular %s', 'graphql-api'), $names_lc),
            'separate_items_with_commas'     => \sprintf(\__('Separate %s with commas', 'graphql-api'), $names_lc),
            'add_or_remove_items'            => \sprintf(\__('Add or remove %s', 'graphql-api'), $name_lc),
            'choose_from_most_used'          => \sprintf(\__('Choose from the most used %s', 'graphql-api'), $names_lc),
            'not_found'                      => \sprintf(\__('No %s found', 'graphql-api'), $names_lc),
        );
    }

    /**
     * Taxonomy name
     */
    abstract public function getTaxonomyName(bool $uppercase = true): string;

    /**
     * Taxonomy plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     */
    abstract protected function getTaxonomyPluralNames(bool $uppercase = true): string;


    /**
     * Initialize the different post types
     */
    public function initTaxonomy(): void
    {
        $names_uc = $this->getTaxonomyPluralNames(true);
        $labels = $this->getTaxonomyLabels(
            $this->getTaxonomyName(true),
            $names_uc,
            $this->getTaxonomyName(false),
            $this->getTaxonomyPluralNames(false)
        );
        $args = array(
            'label' => $names_uc,
            'labels' => $labels,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => false,
            'show_in_rest' => true,
        );
        /**
         * From documentation concerning 2nd parameter ($object_type)
         * > Setting explicitly to null registers the taxonomy but doesn't associate it
         * > with any objects, so it won't be directly available within the Admin UI.>
         * > You will need to manually register it using the 'taxonomy' parameter
         * > (passed through $args)
         * > when registering a custom post_type
         * @see https://codex.wordpress.org/Function_Reference/register_taxonomy#Parameters
         */
        \register_taxonomy(
            $this->getTaxonomy(),
            [],
            $args
        );
    }
}
