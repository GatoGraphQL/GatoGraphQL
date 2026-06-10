<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\ContentProcessors\ContentParserOptions;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\ModuleResolverInterface;

abstract class AbstractExtensionModuleDocumentationMenuPage extends AbstractModuleDocsMenuPage
{
    /**
     * @return array<string,mixed>
     */
    protected function getMarkdownContentOptions(): array
    {
        $options = parent::getMarkdownContentOptions();

        $websiteURL = $this->getDocParamWebsiteURL();
        if ($websiteURL !== null) {
            $options[ContentParserOptions::WEBSITE_DOC_URL] = $websiteURL;
        }

        return $options;
    }

    /**
     * When opening a bundled extension doc via ?doc=..., link the English-doc
     * notice to that extension's page on the localized website.
     */
    protected function getDocParamWebsiteURL(): ?string
    {
        $doc = App::query(RequestParams::DOC, '');
        if ($doc === '') {
            return null;
        }

        if (!preg_match('#extensions/([^/]+)/docs/modules/#', $doc, $matches)) {
            return null;
        }

        $extensionSlug = $matches[1];
        foreach ($this->getModuleRegistry()->getAllModules(true, false, false) as $module) {
            $moduleResolver = $this->getModuleRegistry()->getModuleResolver($module);
            if (!($moduleResolver instanceof ExtensionModuleResolverInterface)) {
                continue;
            }
            if ($moduleResolver->getSlug($module) !== $extensionSlug) {
                continue;
            }
            return $moduleResolver->getWebsiteURL($module);
        }

        return null;
    }

    /**
     * If opening a Tutorial lesson doc in the iframe, do not use tabpanels
     */
    protected function useTabpanelForContent(): bool
    {
        if (
            $this->getMenuPageHelper()->isDocumentationScreen()
            && str_contains(App::query(RequestParams::DOC, ''), '../docs/tutorial/')
        ) {
            return false;
        }

        return parent::useTabpanelForContent();
    }

    protected function getModuleDoesNotExistErrorMessage(string $module): string
    {
        return sprintf(
            \__('Oops, extension \'%s\' does not exist', 'gatographql'),
            $module
        );
    }

    protected function getModuleHasNoDocumentationErrorMessage(
        string $module,
        ModuleResolverInterface $moduleResolver,
    ): string {
        return sprintf(
            \__('Oops, extension \'%s\' has no documentation', 'gatographql'),
            $moduleResolver->getName($module)
        );
    }

    /**
     * Any one document under bundle-extensions. It doesn't matter
     * which one, as all links will start with "../" anyway
     */
    protected function getRelativePathDir(): string
    {
        $anyDocumentFolder = 'access-control';
        return 'extensions/' . $anyDocumentFolder . '/docs/modules/' . $anyDocumentFolder;
    }

    /**
     * Get the URL where to look for the documentation.
     */
    protected function getDocsFolder(): string
    {
        return '';
    }
}
