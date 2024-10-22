<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Taxonomies;

use GatoGraphQL\GatoGraphQL\AppHelpers;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationInterface;
use GatoGraphQL\GatoGraphQL\Services\Menus\MenuInterface;
use GatoGraphQL\GatoGraphQL\Services\Menus\PluginMenu;
use PoP\ComponentModel\App;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Root\Services\StandaloneServiceTrait;

abstract class AbstractTaxonomy extends AbstractAutomaticallyInstantiatedService implements TaxonomyInterface
{
    use StandaloneServiceTrait;

    private ?PluginMenu $pluginMenu = null;
    private ?UserAuthorizationInterface $userAuthorization = null;
    
    final public function setPluginMenu(PluginMenu $pluginMenu): void
    {
        $this->pluginMenu = $pluginMenu;
    }
    final protected function getPluginMenu(): PluginMenu
    {
        if ($this->pluginMenu === null) {
            /** @var PluginMenu */
            $pluginMenu = InstanceManagerFacade::getInstance()->getInstance(PluginMenu::class);
            $this->pluginMenu = $pluginMenu;
        }
        return $this->pluginMenu;
    }
    final public function setUserAuthorization(UserAuthorizationInterface $userAuthorization): void
    {
        $this->userAuthorization = $userAuthorization;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        if ($this->userAuthorization === null) {
            /** @var UserAuthorizationInterface */
            $userAuthorization = InstanceManagerFacade::getInstance()->getInstance(UserAuthorizationInterface::class);
            $this->userAuthorization = $userAuthorization;
        }
        return $this->userAuthorization;
    }

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
            $this->initTaxonomy(...),
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
            'search_items'                   => \sprintf(\__('Search %s', 'gatographql'), $names_uc),
            'all_items'                      => $names_uc,//\sprintf(\__('All %s', 'gatographql'), $names_uc),
            'edit_item'                      => \sprintf(\__('Edit %s', 'gatographql'), $name_uc),
            'update_item'                    => \sprintf(\__('Update %s', 'gatographql'), $name_uc),
            'add_new_item'                   => \sprintf(\__('Add New %s', 'gatographql'), $name_uc),
            'new_item_name'                  => \sprintf(\__('Add New %s', 'gatographql'), $name_uc),
            'view_item'                      => \sprintf(\__('View %s', 'gatographql'), $name_uc),
            'popular_items'                  => \sprintf(\__('Popular %s', 'gatographql'), $names_lc),
            'separate_items_with_commas'     => \sprintf(\__('Separate %s with commas', 'gatographql'), $names_lc),
            'add_or_remove_items'            => \sprintf(\__('Add or remove %s', 'gatographql'), $name_lc),
            'choose_from_most_used'          => \sprintf(\__('Choose from the most used %s', 'gatographql'), $names_lc),
            'not_found'                      => \sprintf(\__('No %s found', 'gatographql'), $names_lc),
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
        $securityTaxonomyArgs = [
            'public' => $this->isPublic(),
            'publicly_queryable' => $this->isPubliclyQueryable(),
        ];
        $canAccessSchemaEditor = $this->getUserAuthorization()->canAccessSchemaEditor();
        $showInMenu = $this->showInMenu();
        $args = array_merge(
            $securityTaxonomyArgs,
            [
                'label' => $names_uc,
                'labels' => $labels,
                'hierarchical' => true,
                'show_tagcloud' => false,
                'show_in_nav_menus' => $showInMenu !== false,
                'show_ui' => $showInMenu !== false,
                'show_in_menu' => $showInMenu,
                'show_in_rest' => $canAccessSchemaEditor,
                'show_admin_column' => $this->showAdminColumn(),
            ]
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
    
    protected function isPublic(): bool
    {
        return true;
    }

    protected function isPubliclyQueryable(): bool
    {
        return $this->isPublic();
    }

    public function getMenu(): MenuInterface
    {
        return $this->getPluginMenu();
    }

    /**
     * Show in menu
     */
    public function showInMenu(): string|false
    {
        $canAccessSchemaEditor = $this->getUserAuthorization()->canAccessSchemaEditor();
        return $canAccessSchemaEditor ? $this->getMenu()->getName() : false;
    }

    protected function showAdminColumn(): bool
    {
        return $this->isHierarchical();
    }

    public function getTaxonomy(): string
    {
        return $this->getTaxonomyNamespace() . '-' . strtolower(str_replace(' ', '-', $this->getTaxonomyName(false)));
    }

    protected function getTaxonomyNamespace(): string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getPluginNamespaceForDB();
    }
}
