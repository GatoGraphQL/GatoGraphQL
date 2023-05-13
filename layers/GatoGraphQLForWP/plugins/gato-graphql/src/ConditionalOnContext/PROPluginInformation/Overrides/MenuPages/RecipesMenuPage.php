<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\Overrides\MenuPages;

use GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\StaticHelpers\PROPluginStaticHelpers;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\RecipesMenuPage as UpstreamRecipesMenuPage;

class RecipesMenuPage extends UpstreamRecipesMenuPage
{
    use UsePRODocsMenuPageTrait;

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
            /** @var ExtensionModuleResolverInterface */
            $extensionModuleResolver = $this->getModuleRegistry()->getModuleResolver($recipeEntryPROExtensionModule);
            $message = sprintf(
                \__('This recipe requires extension <strong>%s</strong> to be installed.', 'gato-graphql'),
                $extensionModuleResolver->getName($recipeEntryPROExtensionModule)
            );
            $button = PROPluginStaticHelpers::getGetExtensionToUnlockAnchorHTML(
                $extensionModuleResolver,
                $recipeEntryPROExtensionModule,
                $buttonClassnames,
            );
        } else {
            /** @var ExtensionModuleResolverInterface */
            $extensionModuleResolver = $this->getModuleRegistry()->getModuleResolver(ExtensionModuleResolver::GATO_GRAPHQL_PRO);
            $message = sprintf(
                \__('This recipe requires <strong>%s</strong> to be installed.', 'gato-graphql'),
                $extensionModuleResolver->getName(ExtensionModuleResolver::GATO_GRAPHQL_PRO)
            );
            $button = PROPluginStaticHelpers::getGoPROToUnlockAnchorHTML($buttonClassnames);
        }
        return sprintf(
            <<<HTML
                <div class="%s">
                    <p>%s %s</p>
                </div>
            HTML,
            'go-pro-highlight' . ($recipeEntryPROExtensionModule !== null ? ' pro-extension' : ''),
            $message,
            $button
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
