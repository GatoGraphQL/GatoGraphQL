<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\ContentProcessors\PluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\Exception\ModuleNotExistsException;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\BundleExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\ModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\PluginStaticModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\Root\App;

abstract class AbstractModuleDocsMenuPage extends AbstractDocsMenuPage
{
    use PluginMarkdownContentRetrieverTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?RequestHelperServiceInterface $requestHelperService = null;

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
    final public function setRequestHelperService(RequestHelperServiceInterface $requestHelperService): void
    {
        $this->requestHelperService = $requestHelperService;
    }
    final protected function getRequestHelperService(): RequestHelperServiceInterface
    {
        if ($this->requestHelperService === null) {
            /** @var RequestHelperServiceInterface */
            $requestHelperService = $this->instanceManager->getInstance(RequestHelperServiceInterface::class);
            $this->requestHelperService = $requestHelperService;
        }
        return $this->requestHelperService;
    }

    /**
     * Validate the param also
     */
    protected function isCurrentScreen(): bool
    {
        if (!parent::isCurrentScreen()) {
            return false;
        }
        return $this->getMenuPageHelper()->isDocumentationScreen();
    }

    protected function openInModalWindow(): bool
    {
        return true;
    }

    protected function printBundleExtensions(): bool
    {
        return true;
    }

    protected function useTabpanelForContent(): bool
    {
        return true;
    }

    protected function getContentToPrint(): string
    {
        $requestedModule = $this->getRequestedModule() ?? '';
        if ($requestedModule === '') {
            return sprintf(
                '<p>%s</p>',
                $this->getModuleCannotBeEmpty()
            );
        }
        $module = urldecode($requestedModule);
        try {
            $moduleResolver = $this->getModuleRegistry()->getModuleResolver($module);
        } catch (ModuleNotExistsException) {
            return sprintf(
                '<p>%s</p>',
                $this->getModuleDoesNotExistErrorMessage($module)
            );
        }
        $hasDocumentation = $moduleResolver->hasDocumentation($module);
        $documentation = $hasDocumentation ? $moduleResolver->getDocumentation($module) : null;
        if ($documentation === null) {
            return sprintf(
                '<p>%s</p>',
                $this->getModuleHasNoDocumentationErrorMessage(
                    $module,
                    $moduleResolver,
                )
            );
        }
        return $documentation;
    }

    /**
     * This is crazy: passing ?module=Foo\Bar\module,
     * and then doing $_GET['module'], returns "Foo\\Bar\\module"
     * So parse the URL to extract the "module" param
     */
    protected function getRequestedModule(): ?string
    {
        /** @var array<string,mixed> */
        $result = [];
        parse_str(App::server('REQUEST_URI'), $result);
        return $result[RequestParams::MODULE] ?? null;
    }

    protected function getModuleCannotBeEmpty(): string
    {
        return sprintf(
            \__('URL param \'%s\' cannot be empty', 'gatographql'),
            RequestParams::MODULE
        );
    }

    protected function getModuleDoesNotExistErrorMessage(string $module): string
    {
        return sprintf(
            \__('Oops, module \'%s\' does not exist', 'gatographql'),
            $module
        );
    }

    protected function getModuleHasNoDocumentationErrorMessage(
        string $module,
        ModuleResolverInterface $moduleResolver,
    ): string {
        return sprintf(
            \__('Oops, module \'%s\' has no documentation', 'gatographql'),
            $moduleResolver->getName($module)
        );
    }

    /**
     * Display the list of bundled extensions.
     */
    protected function getAdditionalContentToPrint(): string
    {
        $requestedModule = $this->getRequestedModule() ?? '';
        if ($requestedModule === '') {
            return '';
        }
        
        $module = urldecode($requestedModule);
        try {
            $moduleResolver = $this->getModuleRegistry()->getModuleResolver($module);
        } catch (ModuleNotExistsException) {
            return '';
        }
        
        $isBundleExtension = $moduleResolver instanceof BundleExtensionModuleResolverInterface;
        if (!$isBundleExtension) {
            return '';
        }

        /** @var BundleExtensionModuleResolverInterface */
        $bundleExtensionModuleResolver = $moduleResolver;
        $bundleExtensionModules = $bundleExtensionModuleResolver->getBundledExtensionModules($module);
        
        $documentation = '';
        $requestedURL = $this->getRequestHelperService()->getRequestedFullURL();
        $bundleURL = \remove_query_arg(
            RequestParams::DOC,
            $requestedURL
        );
        $isPrintingBundledModule = App::query(RequestParams::DOC, '') !== '';
        foreach ($bundleExtensionModules as $bundleExtensionModule) {
            /**
             * The URL is the current one, plus attr to open the .md file
             * in a modal window.
             *
             * Example of rendering this page:
             *
             *   https://mysite.com/wp-admin/admin.php?page=gatographql_extensions&tab=docs&module=GatoGraphQL%5CGatoGraphQL%5Cbundle-extensions%5Cpersisted-queries
             */
            $doc = $this->getBundledExtensionRelativePath($bundleExtensionModule);
            $elementURLParams = [
                RequestParams::DOC => $doc,
            ];
            $internalLink = \add_query_arg(
                $elementURLParams,
                $requestedURL
            );
            
            /** @var ExtensionModuleResolverInterface */
            $extensionModuleResolver = $this->getModuleRegistry()->getModuleResolver($bundleExtensionModule);
            $documentation .= $this->getBundleExtensionItemHTML(
                $extensionModuleResolver->getName($bundleExtensionModule),
                $extensionModuleResolver->getLogoURL($bundleExtensionModule),
                $internalLink,
                $extensionModuleResolver->getWebsiteURL($bundleExtensionModule),
                $extensionModuleResolver->getDescription($bundleExtensionModule),
            );
        }
        
        return sprintf(
            '
            <hr/>
            <h2>%s</h2>
            <div class="wp-list-table widefat gato-graphql_page_gatographql_extensions gatographql-list-table">
			    <div id="the-list">
                    %s
                </div>
            </div>
            ',
            sprintf(
                PluginStaticModuleConfiguration::offerGatoGraphQLPROFeatureBundles()
                    && !PluginStaticModuleConfiguration::offerGatoGraphQLPROBundle()
                    && !PluginStaticModuleConfiguration::offerGatoGraphQLPROAllFeatureExtensionBundle()
                    ? \__('Modules included in the %s extension', 'gatographql')
                    : \__('Modules included in the %s bundle', 'gatographql'),
                $isPrintingBundledModule
                    ? sprintf(
                        '<a href="%s">%s</a>',
                        $bundleURL,
                        $moduleResolver->getName($module)
                    )
                    : $moduleResolver->getName($module)
            ),
            $documentation
        );
    }

    protected function getBundledExtensionRelativePath(string $module): string
    {
        /** @var ExtensionModuleResolverInterface */
        $extensionModuleResolver = $this->getModuleRegistry()->getModuleResolver($module);
        return sprintf(
            '../../../../../extensions/%1$s/docs/modules/%1$s',
            $extensionModuleResolver->getSlug($module)
        );
    }

    protected function getBundleExtensionItemHTML(
        string $title,
        string $logoURL,
        string $internalLink,
        string $externalLink,
        string $description,
    ): string {
        $itemHTMLPlaceholder = '
            <div class="plugin-card plugin-card-non-installed">
                <div class="plugin-card-top">
                    <div class="name column-name">
                        <a href="%4$s">
                            <h3>
                                %1$s
                                <img src="%2$s" class="plugin-icon" alt="">
                            </h3>
                        </a>
                    </div>
                    <div class="action-links">
                        <ul class="plugin-action-buttons">
                            <li>
                                <a class="install-now button" href="%5$s" aria-label="%1$s" target="_blank">
                                    %6$s
                                </a>
                            </li>
                            <li>
                                <a href="%4$s" aria-label="%1$s">
                                    %7$s
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="desc column-description">
                        <p>%3$s</p>
                    </div>
                </div>
                <div class="plugin-card-bottom">
                    <div class="column-compatibility">
                        %8$s
                    </div>
                </div>
            </div>
        ';

        return sprintf(
            $itemHTMLPlaceholder,
            $title,
            $logoURL,
            $description,
            $internalLink,
            $externalLink,
            \__('Website', 'gatographql') . HTMLCodes::OPEN_IN_NEW_WINDOW,
            \__('More details', 'gatographql'),
            sprintf(
                '<span class="compatibility-compatible">%s</span>',
                \__('<strong>Compatible</strong> with your version of WordPress')
            )
        );
    }
}
