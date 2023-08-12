<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Admin\Tables\ExtensionListTable;
use GatoGraphQL\GatoGraphQL\Admin\Tables\ItemListTableInterface;
use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\PluginApp;

/**
 * Extension menu page
 */
class ExtensionsMenuPage extends AbstractTableMenuPage
{
    use OpenInModalTriggerMenuPageTrait;

    public final const SCREEN_OPTION_NAME = 'gato_graphql_extensions_per_page';

    private ?ExtensionDocsMenuPage $extensionDocsMenuPage = null;

    final public function setExtensionDocsMenuPage(ExtensionDocsMenuPage $extensionDocsMenuPage): void
    {
        $this->extensionDocsMenuPage = $extensionDocsMenuPage;
    }
    final protected function getExtensionDocsMenuPage(): ExtensionDocsMenuPage
    {
        if ($this->extensionDocsMenuPage === null) {
            /** @var ExtensionDocsMenuPage */
            $extensionDocsMenuPage = $this->instanceManager->getInstance(ExtensionDocsMenuPage::class);
            $this->extensionDocsMenuPage = $extensionDocsMenuPage;
        }
        return $this->extensionDocsMenuPage;
    }

    public function getMenuPageSlug(): string
    {
        return 'extensions';
    }

    protected function getHeader(): string
    {
        return \__('Gato GraphQL — Extensions', 'gato-graphql');
    }

    protected function hasViews(): bool
    {
        return false;
    }

    protected function getScreenOptionLabel(): string
    {
        return \__('Extensions', 'gato-graphql');
    }

    /**
     * Validate the param also
     */
    protected function isCurrentScreen(): bool
    {
        return !$this->getMenuPageHelper()->isDocumentationScreen() && parent::isCurrentScreen();
    }

    protected function getScreenOptionName(): string
    {
        return self::SCREEN_OPTION_NAME;
    }

    /**
     * @return class-string<ItemListTableInterface>
     */
    protected function getTableClass(): string
    {
        return ExtensionListTable::class;
    }

    // protected function showScreenOptions(): bool
    // {
    //     return true;
    // }

    /**
     * Enqueue the required assets
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $this->enqueueModalTriggerAssets();
        $this->enqueueExtensionAssets();
    }

    protected function enqueueExtensionAssets(): void
    {
        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();

        /**
         * Hide the bottom part of the extension items on the table,
         * as it contains unneeded information, and just hiding it
         * is easier than editing the PHP code
         */
        \wp_enqueue_style(
            'gato-graphql-extensions',
            $mainPluginURL . 'assets/css/extensions.css',
            array(),
            $mainPluginVersion
        );
    }

    protected function printHeader(): void
    {
        parent::printHeader();

        printf(
            '<p>%s</p>',
            sprintf(
                __('%s <a href="%s" class="button">Switch to the <strong>Extension Docs</strong> view</a>', 'gato-graphql'),
                $this->getHeaderMessage(),
                \admin_url(sprintf(
                    'admin.php?page=%s',
                    $this->getExtensionDocsMenuPage()->getScreenID()
                )),
            )
        );
    }

    public function getHeaderMessage(): string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return sprintf(
            __('Extensions add functionality and expand the GraphQL schema. Browse all bundles and extensions on the <a href="%1$s" target="%2$s">Gato GraphQL website%3$s</a>.', 'gato-graphql'),
            $moduleConfiguration->getGatoGraphQLExtensionsPageURL(),
            '_blank',
            HTMLCodes::OPEN_IN_NEW_WINDOW,
        );
    }
}
