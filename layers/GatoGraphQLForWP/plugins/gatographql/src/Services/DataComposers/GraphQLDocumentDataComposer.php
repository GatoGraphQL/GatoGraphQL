<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\DataComposers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\BundleExtensionModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\DataProviders\RecipeDataProvider;
use PoP\Root\Services\BasicServiceTrait;
use RuntimeException;

class GraphQLDocumentDataComposer
{
    use BasicServiceTrait;
    
    public const GRAPHQL_DOCUMENT_HEADER_SEPARATOR = '########################################################################';

    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?RecipeDataProvider $recipeDataProvider = null;

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
    final public function setRecipeDataProvider(RecipeDataProvider $recipeDataProvider): void
    {
        $this->recipeDataProvider = $recipeDataProvider;
    }
    final protected function getRecipeDataProvider(): RecipeDataProvider
    {
        if ($this->recipeDataProvider === null) {
            /** @var RecipeDataProvider */
            $recipeDataProvider = $this->instanceManager->getInstance(RecipeDataProvider::class);
            $this->recipeDataProvider = $recipeDataProvider;
        }
        return $this->recipeDataProvider;
    }

    /**
     * Append the required extensions and bundles to the header
     * in the persisted query.
     */
    public function addRequiredBundlesAndExtensionsToGraphQLDocumentHeader(
        string $graphQLDocument,
        string $recipeSlug,
    ): string {
        /**
         * Check if there are required extensions for the recipe
         */
        $recipeSlugDataItems = $this->getRecipeDataProvider()->getRecipeSlugDataItems();
        $recipeDataItem = $recipeSlugDataItems[$recipeSlug] ?? null;
        if ($recipeDataItem === null) {
            throw new RuntimeException(
                sprintf(
                    \__('There is no recipe with slug "%s"', 'gatographql'),
                    $recipeSlug
                )
            );
        }
        $requiredExtensionModules = $recipeDataItem[1] ?? [];
        if ($requiredExtensionModules === []) {
            return $graphQLDocument;
        }
        $requiredBundleModules = $recipeDataItem[2] ?? [];
        $requiredBundleModules[] = BundleExtensionModuleResolver::ALL_EXTENSIONS;

        /**
         * Find the last instance of the header separator
         * (there will normally be two instances)
         */
        $pos = strrpos(
            $graphQLDocument,
            self::GRAPHQL_DOCUMENT_HEADER_SEPARATOR
        );
        if ($pos === false) {
            // There is no header => throw error!
            throw new RuntimeException(
                sprintf(
                    \__('There is no header in GraphQL document for recipe "%s": %s%s'),
                    $recipeSlug,
                    PHP_EOL,
                    $graphQLDocument
                )
            );
        }

        $headerRequirementsSectionItems = [
            '*********************************************************************',
            '',
            \__('Required Extensions:', 'gatographql'),
        ];

        foreach ($requiredExtensionModules as $extensionModule) {
            /** @var ExtensionModuleResolverInterface */
            $extensionModuleResolver = $this->getModuleRegistry()->getModuleResolver($extensionModule);
            $headerRequirementsSectionItems[] = sprintf(
                \__('  - %s', 'gatographql'),
                $extensionModuleResolver->getName($extensionModule)
            );
        }
        
        $headerRequirementsSectionItems[] = '';
        $headerRequirementsSectionItems[] = \__('These Extensions are all included in any of these Bundles:', 'gatographql');
        
        foreach ($requiredBundleModules as $bundleModule) {
            /** @var ExtensionModuleResolverInterface */
            $bundleModuleResolver = $this->getModuleRegistry()->getModuleResolver($bundleModule);
            $headerRequirementsSectionItems[] = sprintf(
                \__('  - %s', 'gatographql'),
                $bundleModuleResolver->getName($bundleModule)
            );
        }
        
        $headerRequirementsSectionItems[] = '';

        return substr(
            $graphQLDocument,
            0,
            $pos
        )
        . implode(
            PHP_EOL,
            array_map(
                fn (string $item) => '# ' . $item,
                $headerRequirementsSectionItems
            )
        )
        . PHP_EOL
        . substr(
            $graphQLDocument,
            $pos
        );
    }

    /**
     * Escape characters to display them correctly inside the client in the block
     */
    public function encodeGraphQLDocumentForOutput(string $graphQLDocument): string
    {
        return str_replace(
            [
                PHP_EOL,
                '"',
                '&',
            ],
            [
                '\\n',
                '\"',
                '\&',
            ],
            $graphQLDocument
        );
    }

    /**
     * Escape characters to display them correctly inside the client in the block
     */
    public function encodeGraphQLVariablesJSONForOutput(string $graphQLVariablesJSON): string
    {
        return str_replace(
            [
                PHP_EOL,
                '"',
            ],
            [
                '\\n',
                '\"',
            ],
            $graphQLVariablesJSON
        );
    }
}
