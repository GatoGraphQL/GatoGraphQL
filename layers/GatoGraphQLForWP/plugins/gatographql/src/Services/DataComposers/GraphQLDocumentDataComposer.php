<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\DataComposers;

class GraphQLDocumentDataComposer
{
    public const GRAPHQL_DOCUMENT_HEADER_SEPARATOR = '########################################################################';
    
    /**
     * Append the required extensions and bundles to the header
     * in the persisted query.
     */
    public function addRequiredBundlesAndExtensionsToGraphQLDocumentHeader(
        string $graphQLDocument,
        string $recipeFileSlug,
    ): string {
        return str_replace(
            '',
            '',
            $graphQLDocument
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
