<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\DataComposers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\Aggregators\BundleExtensionAggregator;
use GatoGraphQL\GatoGraphQL\Services\DataProviders\RecipeDataProvider;
use GatoGraphQL\GatoGraphQL\Services\DataProviders\VirtualRecipeDataProvider;
use PoP\Root\Services\BasicServiceTrait;
use RuntimeException;

class GraphQLDocumentDataComposer
{
    use BasicServiceTrait;

    public const GRAPHQL_DOCUMENT_HEADER_SEPARATOR = '########################################################################';
    public const GRAPHQL_DOCUMENT_INNER_SEPARATOR = '*********************************************************************';

    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?RecipeDataProvider $recipeDataProvider = null;
    private ?VirtualRecipeDataProvider $virtualRecipeDataProvider = null;
    private ?BundleExtensionAggregator $bundleExtensionAggregator = null;

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
    final public function setVirtualRecipeDataProvider(VirtualRecipeDataProvider $virtualRecipeDataProvider): void
    {
        $this->virtualRecipeDataProvider = $virtualRecipeDataProvider;
    }
    final protected function getVirtualRecipeDataProvider(): VirtualRecipeDataProvider
    {
        if ($this->virtualRecipeDataProvider === null) {
            /** @var VirtualRecipeDataProvider */
            $virtualRecipeDataProvider = $this->instanceManager->getInstance(VirtualRecipeDataProvider::class);
            $this->virtualRecipeDataProvider = $virtualRecipeDataProvider;
        }
        return $this->virtualRecipeDataProvider;
    }
    final public function setBundleExtensionAggregator(BundleExtensionAggregator $bundleExtensionAggregator): void
    {
        $this->bundleExtensionAggregator = $bundleExtensionAggregator;
    }
    final protected function getBundleExtensionAggregator(): BundleExtensionAggregator
    {
        if ($this->bundleExtensionAggregator === null) {
            /** @var BundleExtensionAggregator */
            $bundleExtensionAggregator = $this->instanceManager->getInstance(BundleExtensionAggregator::class);
            $this->bundleExtensionAggregator = $bundleExtensionAggregator;
        }
        return $this->bundleExtensionAggregator;
    }

    /**
     * Append the required extensions and bundles to the header
     * in the persisted query.
     *
     * @param string[]|null $skipExtensionModules Extensions that must not be added to the Persisted Query (which are associated to the tutorial lesson)
     */
    public function addRequiredBundlesAndExtensionsToGraphQLDocumentHeader(
        string $graphQLDocument,
        string $recipeSlug,
        ?array $skipExtensionModules = null
    ): string {
        /**
         * Check if there are required extensions for the tutorial lesson
         */
        $recipeSlugDataItems = [
            ...$this->getRecipeDataProvider()->getRecipeSlugDataItems(),
            ...$this->getVirtualRecipeDataProvider()->getRecipeSlugDataItems(),
        ];
        $recipeDataItem = $recipeSlugDataItems[$recipeSlug] ?? null;
        if ($recipeDataItem === null) {
            throw new RuntimeException(
                sprintf(
                    \__('There is no tutorial lesson with slug "%s"', 'gatographql'),
                    $recipeSlug
                )
            );
        }
        $requiredExtensionModules = $recipeDataItem[1] ?? [];
        if ($requiredExtensionModules === []) {
            return $graphQLDocument;
        }
        if ($skipExtensionModules !== null) {
            $requiredExtensionModules = array_values(array_diff(
                $requiredExtensionModules,
                $skipExtensionModules
            ));
        }
        $requiredBundleModules = $this->getBundleExtensionAggregator()->getBundleModulesComprisingAllExtensionModules($requiredExtensionModules);

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
                    \__('There is no header in GraphQL document for tutorial lesson "%s": %s%s'),
                    $recipeSlug,
                    PHP_EOL,
                    $graphQLDocument
                )
            );
        }

        $headerRequirementsSectionItems = [
            self::GRAPHQL_DOCUMENT_INNER_SEPARATOR,
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
                '\\',
                PHP_EOL,
                '"',
                '&',
                '-',
                '<',
                '>',
            ],
            [
                '\\\\',
                '\\n',
                '\"',
                '\&',
                '\u002d',
                '\<',
                '\>',
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
