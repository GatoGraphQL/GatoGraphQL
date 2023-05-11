<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Admin\Tables\ExtensionListTable;
use GatoGraphQL\GatoGraphQL\Admin\Tables\ItemListTableInterface;
use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\PluginApp;

/**
 * Extension menu page
 */
class ExtensionsMenuPage extends AbstractTableMenuPage
{
    use OpenInModalTriggerMenuPageTrait;

    public final const SCREEN_OPTION_NAME = 'gato_graphql_extensions_per_page';

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
        $mainPluginURL = PluginApp::getMainPlugin()->getPluginURL();
        $mainPluginVersion = PluginApp::getMainPlugin()->getPluginVersion();

        /**
         * Hide the bottom part of the extension items on the table,
         * as it contains unneeded information, and just hiding it
         * is easier than editing the PHP code
         */
        \wp_enqueue_style(
            'gato-graphql-extensions',
            $mainPluginURL . 'assets-pro/css/extensions.css',
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
                __('Extensions add functionality and expand the GraphQL schema. You can browse and get extensions on the <a href="%s" target="%s">Gato GraphQL shop%s</a>.', 'gato-graphql'),
                'https://gatographql.com',
                '_blank',
                HTMLCodes::OPEN_IN_NEW_WINDOW
            )
        );
    }
}
