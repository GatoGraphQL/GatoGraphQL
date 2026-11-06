<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Validation;

use PoP\ComponentModel\Feedback\QueryFeedback;
use PoP\GraphQLParser\ExtendedSpec\Execution\ExecutableDocumentInterface;

/**
 * Validate the requested operations against the schema,
 * before executing them, to find the "request errors"
 * defined in the GraphQL spec:
 *
 * - fields that do not exist in the type
 * - relational fields queried on a leaf type
 * - non-existing, missing or uncoercible arguments
 * - non-existing, misplaced or repeated directives,
 *   and their arguments
 *
 * @see https://spec.graphql.org/draft/#sec-Validation
 */
interface RequestValidationServiceInterface
{
    /**
     * If option "Return no data on request errors" is enabled,
     * validate the requested operations against the schema,
     * and add the errors to the document feedback.
     *
     * @return bool `false` if the query must not be executed
     */
    public function maybeValidateExecutableDocumentAgainstSchema(ExecutableDocumentInterface $executableDocument): bool;

    /**
     * @return QueryFeedback[]
     */
    public function validateExecutableDocumentAgainstSchema(ExecutableDocumentInterface $executableDocument): array;
}
