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

    public final const SCREEN_OPTION_NAME = 'gatographql_extensions_per_page';

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
        return \__('Gato GraphQL — Extensions', 'gatographql');
    }

    protected function hasViews(): bool
    {
        return false;
    }

    protected function getScreenOptionLabel(): string
    {
        return \__('Extensions', 'gatographql');
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
            'gatographql-extensions',
            $mainPluginURL . 'assets/css/extensions.css',
            array(),
            $mainPluginVersion
        );
    }

    protected function printHeader(): void
    {
        parent::printHeader();

        $headerMessage_safe = $this->getHeaderMessage();
        $extensionDocsURL = \admin_url(sprintf(
            'admin.php?page=%s',
            $this->getExtensionDocsMenuPage()->getScreenID()
        ));
        $label_safe = __('Switch to the <strong>Extension Docs</strong> view', 'gatographql');
        ?>
            <p>
                <?php echo $headerMessage_safe ?>
                <a href="<?php echo esc_url($extensionDocsURL) ?>" class="button"><?php echo $label_safe ?></a>
            </p>
        <?php
    }

    public function getHeaderMessage(): string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return sprintf(
            __('Extensions add functionality and expand the GraphQL schema. Browse all <a href="%1$s" target="%3$s">bundles%4$s</a> and <a href="%2$s" target="%3$s">extensions%4$s</a> on the Gato GraphQL website.', 'gatographql'),
            $moduleConfiguration->getGatoGraphQLBundlesPageURL(),
            $moduleConfiguration->getGatoGraphQLExtensionsPageURL(),
            '_blank',
            HTMLCodes::OPEN_IN_NEW_WINDOW,
        );
    }
}
