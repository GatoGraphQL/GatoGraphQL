<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\BlockCategories\BlockCategoryInterface;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\PersistedQueryEndpointBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;

/**
 * GraphiQL block
 */
abstract class AbstractGraphiQLBlock extends AbstractBlock
{
    use MainPluginBlockTrait;

    public final const ATTRIBUTE_NAME_QUERY = 'query';
    public final const ATTRIBUTE_NAME_VARIABLES = 'variables';

    private ?EndpointHelpers $endpointHelpers = null;
    private ?PersistedQueryEndpointBlockCategory $persistedQueryEndpointBlockCategory = null;

    final public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        return $this->endpointHelpers ??= $this->instanceManager->getInstance(EndpointHelpers::class);
    }
    final public function setPersistedQueryEndpointBlockCategory(PersistedQueryEndpointBlockCategory $persistedQueryEndpointBlockCategory): void
    {
        $this->persistedQueryEndpointBlockCategory = $persistedQueryEndpointBlockCategory;
    }
    final protected function getPersistedQueryEndpointBlockCategory(): PersistedQueryEndpointBlockCategory
    {
        return $this->persistedQueryEndpointBlockCategory ??= $this->instanceManager->getInstance(PersistedQueryEndpointBlockCategory::class);
    }

    protected function getBlockName(): string
    {
        return 'graphiql';
    }

    protected function getBlockCategory(): ?BlockCategoryInterface
    {
        return $this->getPersistedQueryEndpointBlockCategory();
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    protected function getAdminGraphQLEndpoint(): string
    {
        return $this->getEndpointHelpers()->getAdminConfigurableSchemaGraphQLEndpoint();
    }

    /**
     * Pass localized data to the block
     *
     * @return array<string, mixed>
     */
    protected function getLocalizedData(): array
    {
        return array_merge(
            parent::getLocalizedData(),
            [
                'nonce' => \wp_create_nonce('wp_rest'),
                'endpoint' => $this->getAdminGraphQLEndpoint(),
                'defaultQuery' => $this->getDefaultQuery(),
            ]
        );
    }

    /**
     * Load index.css from editor.scss
     */
    protected function registerEditorCSS(): bool
    {
        return true;
    }

    /**
     * GraphiQL default query
     */
    protected function getDefaultQuery(): string
    {
        /**
         * Temporarily print nothing, until "Ctrl+A" works well:
         * @see https://github.com/WordPress/gutenberg/issues/22689
         *
         * When fixed, uncomment the code below.
         *
         * @see https://github.com/leoloso/PoP/issues/251
         */
        return '';
    //     return <<<EOT
    //         # Welcome to GraphiQL
    //         #
    //         # GraphiQL is an in-browser tool for writing, validating, and
    //         # testing GraphQL queries.
    //         #
    //         # Type queries into this side of the screen, and you will see intelligent
    //         # typeaheads aware of the current GraphQL type schema and live syntax and
    //         # validation errors highlighted within the text.
    //         #
    //         # GraphQL queries typically start with a "{" character. Lines that starts
    //         # with a # are ignored.
    //         #
    //         # An example GraphQL query might look like:
    //         #
    //         #   {
    //         #     field(arg: "value") {
    //         #       subField
    //         #     }
    //         #   }
    //         #
    //         # Run the query (at any moment):
    //         #
    //         #   Ctrl-Enter (or press the play button above)
    //         #

    //         EOT;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        $content = sprintf(
            '<div class="%s">',
            $this->getBlockClassName() . ' ' . $this->getAlignClassName()
        );
        $query = $attributes[self::ATTRIBUTE_NAME_QUERY] ?? '';
        $variables = $attributes[self::ATTRIBUTE_NAME_VARIABLES] ?? null;
        $content .= sprintf(
            '<p><strong>%s</strong></p>',
            \__('GraphQL Query:', 'graphql-api')
        ) . (
            $query ? sprintf(
                '<pre><code class="prettyprint language-graphql">%s</code></pre>',
                $query
            ) : sprintf(
                '<p><em>%s</em></p>',
                \__('(not set)', 'graphql-api')
            )
        );
        if ($variables) {
            $content .= sprintf(
                '<p><strong>%s</strong></p>',
                \__('Variables:', 'graphql-api')
            ) . sprintf(
                '<pre><code class="prettyprint language-json">%s</code></pre>',
                $variables
            );
        }
        $content .= '</div>';
        return $content;
    }
}
