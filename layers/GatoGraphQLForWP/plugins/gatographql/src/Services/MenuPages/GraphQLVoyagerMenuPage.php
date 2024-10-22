<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;

/**
 * Voyager page
 */
class GraphQLVoyagerMenuPage extends AbstractPluginMenuPage
{
    use EnqueueReactMenuPageTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }

    public function isServiceEnabled(): bool
    {
        $isPrivateEndpointDisabled = !$this->getModuleRegistry()->isModuleEnabled(EndpointFunctionalityModuleResolver::PRIVATE_ENDPOINT);
        if ($isPrivateEndpointDisabled) {
            return false;
        }
        return parent::isServiceEnabled();
    }

    public function print(): void
    {
        ?>
        <div id="voyager" class="voyager-client">
            <p>
                <?php esc_html_e('Loading...', 'gatographql') ?>
            </p>
        </div>
        <?php
    }

    public function getMenuPageSlug(): string
    {
        return 'voyager';
    }

    public function getMenuPageTitle(): string
    {
        return __('Schema', 'gatographql');
    }

    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();

        // CSS
        \wp_enqueue_style(
            'gatographql-voyager-client',
            $mainPluginURL . 'assets/css/voyager-client.css',
            array(),
            $mainPluginVersion
        );
        \wp_enqueue_style(
            'gatographql-voyager',
            $mainPluginURL . 'vendor/graphql-by-pop/graphql-clients-for-wp/clients/voyager/assets/vendors/voyager.css',
            array(),
            $mainPluginVersion
        );

        // JS: execute them all in the footer
        $this->enqueueReactAssets(true);
        \wp_enqueue_script(
            'gatographql-voyager',
            $mainPluginURL . 'vendor/graphql-by-pop/graphql-clients-for-wp/clients/voyager/assets/vendors/voyager.standalone.js',
            array('gatographql-react-dom'),
            $mainPluginVersion,
            true
        );
        \wp_enqueue_script(
            'gatographql-voyager-client',
            $mainPluginURL . 'assets/js/voyager-client.js',
            array('gatographql-voyager'),
            $mainPluginVersion,
            true
        );

        // Load data into the script
        \wp_localize_script(
            'gatographql-voyager-client',
            'graphQLByPoPGraphiQLSettings',
            array(
                'nonce' => \wp_create_nonce('wp_rest'),
                'endpoint' => $this->getEndpointHelpers()->getAdminGraphQLEndpoint(),
            )
        );
    }
}
