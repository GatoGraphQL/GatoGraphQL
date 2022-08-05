<?php

declare(strict_types=1);

namespace PoPAPI\ObjectModels\GraphQLQueryParsingPayload;

use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;

class GraphQLQueryParsingPayload
{
    public function __construct(
        public readonly ExecutableDocument $executableDocument,
    ) {
    }
}
