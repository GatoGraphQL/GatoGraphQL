<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\Overrides\MenuPages;

use GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\StaticHelpers\PROPluginStaticHelpers;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\RecipesMenuPage as UpstreamRecipesMenuPage;
use PoP\Root\Exception\ShouldNotHappenException;

class RecipesMenuPage extends UpstreamRecipesMenuPage
{
    use UsePRODocsMenuPageTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        /** @var ModuleRegistryInterface */
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }

    protected function getRecipeTitleForNavbar(
        string $recipeEntryTitle,
        bool $recipeEntryIsPRO,
        ?string $recipeEntryPROExtensionModule,
    ): string {
        $recipeEntryTitle = parent::getRecipeTitleForNavbar(
            $recipeEntryTitle,
            $recipeEntryIsPRO,
            $recipeEntryPROExtensionModule,
        );
        if (!$recipeEntryIsPRO) {
            return $recipeEntryTitle;
        }
        return PROPluginStaticHelpers::getPROTitle(
            $recipeEntryTitle,
            $recipeEntryPROExtensionModule,
        );
    }

    protected function getRecipeContent(
        string $recipeContent,
        bool $recipeEntryIsPRO,
        ?string $recipeEntryPROExtensionModule,
    ): string {
        $recipeContent = parent::getRecipeContent(
            $recipeContent,
            $recipeEntryIsPRO,
            $recipeEntryPROExtensionModule,
        );
        if (!$recipeEntryIsPRO) {
            return $recipeContent;
        }
        $buttonClassnames = 'button button-secondary';
        if ($recipeEntryPROExtensionModule !== null) {
            $extensionModuleResolver = $this->getModuleRegistry()->getModuleResolver($recipeEntryPROExtensionModule);
            if (!($extensionModuleResolver instanceof ExtensionModuleResolverInterface)) {
                throw new ShouldNotHappenException(
                    sprintf(
                        \__('Module "%s" must implement interface "%s"', 'gato-graphql'),
                        $recipeEntryPROExtensionModule,
                        ExtensionModuleResolverInterface::class
                    )
                );
            }
            $message = sprintf(
                \__('This recipe requires extension "%s" to be installed.', 'gato-graphql'),
                $extensionModuleResolver->getName($recipeEntryPROExtensionModule)
            );
            $buttonHTML = PROPluginStaticHelpers::getGetExtensionToUnlockAnchorHTML(
                $extensionModuleResolver,
                $recipeEntryPROExtensionModule,
                $buttonClassnames,
            );
        } else {
            $message = \__('This recipe requires features available in the Gato GraphQL PRO.', 'gato-graphql');
            $buttonHTML = PROPluginStaticHelpers::getGoPROToUnlockAnchorHTML($buttonClassnames);
        }
        return sprintf(
            <<<HTML
                <div class="go-pro-highlight">
                    <p>%s %s</p>
                </div>
            HTML,
            $message,
            $buttonHTML
        ) . $recipeContent;
    }

    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $this->enqueuePRODocsAssets();
    }
}
