<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks;

use GraphQLAPI\GraphQLAPI\Blocks\AbstractBlock;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Blocks\GraphQLByPoPBlockTrait;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\BlockCategories\AbstractBlockCategory;
use GraphQLAPI\GraphQLAPI\BlockCategories\PersistedQueryBlockCategory;

/**
 * GraphiQL block
 */
abstract class AbstractGraphiQLBlock extends AbstractBlock
{
    use GraphQLByPoPBlockTrait;

    public const ATTRIBUTE_NAME_QUERY = 'query';
    public const ATTRIBUTE_NAME_VARIABLES = 'variables';

    protected EndpointHelpers $endpointHelpers;

    function __construct(EndpointHelpers $endpointHelpers)
    {
        $this->endpointHelpers = $endpointHelpers;
    }

    protected function getBlockName(): string
    {
        return 'graphiql';
    }

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var PersistedQueryBlockCategory
         */
        $blockCategory = $instanceManager->getInstance(PersistedQueryBlockCategory::class);
        return $blockCategory;
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    protected function getAdminGraphQLEndpoint(): string
    {
        return $this->endpointHelpers->getAdminGraphQLEndpoint(true);
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
     *
     * @return boolean
     */
    protected function registerEditorCSS(): bool
    {
        return true;
    }

    /**
     * GraphiQL default query
     *
     * @return string
     */
    protected function getDefaultQuery(): string
    {
        // Temporarily print nothing, until "Ctrl+A" works well:
        // @see https://github.com/WordPress/gutenberg/issues/22689
        return '';
        return <<<EOT
            # Welcome to GraphiQL
            #
            # GraphiQL is an in-browser tool for writing, validating, and
            # testing GraphQL queries.
            #
            # Type queries into this side of the screen, and you will see intelligent
            # typeaheads aware of the current GraphQL type schema and live syntax and
            # validation errors highlighted within the text.
            #
            # GraphQL queries typically start with a "{" character. Lines that starts
            # with a # are ignored.
            #
            # An example GraphQL query might look like:
            #
            #   {
            #     field(arg: "value") {
            #       subField
            #     }
            #   }
            #
            # Run the query (at any moment):
            #
            #   Ctrl-Enter (or press the play button above)
            #

            EOT;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        $content = sprintf(
            '<div class="%s">',
            $this->getBlockClassName() . ' ' . $this->getAlignClass()
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
