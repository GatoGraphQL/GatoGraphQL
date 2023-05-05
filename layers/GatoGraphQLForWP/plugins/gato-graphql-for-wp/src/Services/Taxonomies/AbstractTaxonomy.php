<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Taxonomies;

use GatoGraphQL\GatoGraphQL\AppHelpers;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractTaxonomy extends AbstractAutomaticallyInstantiatedService implements TaxonomyInterface
{
    /**
     * Add the hook to initialize the different taxonomies
     */
    final public function initialize(): void
    {
        /**
         * Only initialize once, for the main AppThread
         */
        if (!AppHelpers::isMainAppThread()) {
            return;
        }

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
     * @return array<string,string>
     */
    protected function getTaxonomyLabels(string $name_uc, string $names_uc, string $name_lc, string $names_lc): array
    {
        return array(
            'name'                           => $names_uc,
            'singular_name'                  => $name_uc,
            'menu_name'                      => $names_uc,
            'search_items'                   => \sprintf(\__('Search %s', 'gato-graphql'), $names_uc),
            'all_items'                      => $names_uc,//\sprintf(\__('All %s', 'gato-graphql'), $names_uc),
            'edit_item'                      => \sprintf(\__('Edit %s', 'gato-graphql'), $name_uc),
            'update_item'                    => \sprintf(\__('Update %s', 'gato-graphql'), $name_uc),
            'add_new_item'                   => \sprintf(\__('Add New %s', 'gato-graphql'), $name_uc),
            'new_item_name'                  => \sprintf(\__('Add New %s', 'gato-graphql'), $name_uc),
            'view_item'                      => \sprintf(\__('View %s', 'gato-graphql'), $name_uc),
            'popular_items'                  => \sprintf(\__('Popular %s', 'gato-graphql'), $names_lc),
            'separate_items_with_commas'     => \sprintf(\__('Separate %s with commas', 'gato-graphql'), $names_lc),
            'add_or_remove_items'            => \sprintf(\__('Add or remove %s', 'gato-graphql'), $name_lc),
            'choose_from_most_used'          => \sprintf(\__('Choose from the most used %s', 'gato-graphql'), $names_lc),
            'not_found'                      => \sprintf(\__('No %s found', 'gato-graphql'), $names_lc),
        );
    }

    /**
     * Initialize the different post types
     */
    protected function initTaxonomy(): void
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
            'show_admin_column' => $this->showAdminColumn(),
        );
        /**
         * From documentation concerning 2nd parameter ($object_type):
         *
         *   > Setting explicitly to null registers the taxonomy but doesn't associate it
         *   > with any objects, so it won't be directly available within the Admin UI.>
         *   > You will need to manually register it using the 'taxonomy' parameter
         *   > (passed through $args)
         *   > when registering a custom post_type
         *
         * @see https://codex.wordpress.org/Function_Reference/register_taxonomy#Parameters
         */
        \register_taxonomy(
            $this->getTaxonomy(),
            $this->getCustomPostTypes(),
            $args
        );
    }

    protected function showAdminColumn(): bool
    {
        return $this->isHierarchical();
    }
}
