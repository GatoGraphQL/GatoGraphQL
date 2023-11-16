<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\DataComposers;

use RuntimeException;

class GraphQLDocumentDataComposer
{
    public const GRAPHQL_DOCUMENT_HEADER_SEPARATOR = '########################################################################';

    /**
     * Append the required extensions and bundles to the header
     * in the persisted query.
     */
    public function addRequiredBundlesAndExtensionsToGraphQLDocumentHeader(
        string $graphQLDocument,
        string $recipeSlug,
    ): string {
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
        ];
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
            ],
            [
                '\\n',
                '\"',
            ],
            $graphQLDocument
        );
    }
}
