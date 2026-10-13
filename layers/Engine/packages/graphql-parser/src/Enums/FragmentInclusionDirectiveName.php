<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Enums;

/**
 * The directives that can be applied to fragment spreads
 * and inline fragments, to include or skip them
 *
 * @see https://spec.graphql.org/September2025/#sec--skip
 * @see https://spec.graphql.org/September2025/#sec--include
 */
enum FragmentInclusionDirectiveName: string
{
    case Include = 'include';
    case Skip = 'skip';
}
