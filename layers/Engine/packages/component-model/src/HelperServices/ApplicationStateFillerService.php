<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoPAPI\API\QueryParsing\GraphQLParserHelperServiceInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\ComponentModel\Feedback\DocumentFeedback;
use PoP\ComponentModel\Feedback\QueryFeedback;
use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\GraphQLParser\Exception\AbstractASTNodeException;
use PoP\GraphQLParser\Exception\AbstractQueryException;
use PoP\GraphQLParser\Exception\Parser\AbstractASTNodeParserException;
use PoP\GraphQLParser\Exception\Parser\AbstractParserException;

class ApplicationStateFillerService implements ApplicationStateFillerServiceInterface
{
    use BasicServiceTrait;

    private ?GraphQLParserHelperServiceInterface $graphQLParserHelperService = null;

    final public function setGraphQLParserHelperService(GraphQLParserHelperServiceInterface $graphQLParserHelperService): void
    {
        $this->graphQLParserHelperService = $graphQLParserHelperService;
    }
    final protected function getGraphQLParserHelperService(): GraphQLParserHelperServiceInterface
    {
        /** @var GraphQLParserHelperServiceInterface */
        return $this->graphQLParserHelperService ??= $this->instanceManager->getInstance(GraphQLParserHelperServiceInterface::class);
    }

    /**
     * @param array<string,mixed> $variables
     */
    public function defineGraphQLQueryVarsInApplicationState(
        string|ExecutableDocument $queryOrExecutableDocument,
        array $variables = [],
        ?string $operationName = null
    ): void {
        $passingQuery = is_string($queryOrExecutableDocument);
        if ($passingQuery) {
            $query = $queryOrExecutableDocument;
            $executableDocument = null;
        } else {
            $executableDocument = $queryOrExecutableDocument;
            $query = $executableDocument->getDocument()->asDocumentString();
        }

        // Override the state
        $appStateManager = App::getAppStateManager();
        $appStateManager->override('query', $query);
        $appStateManager->override('variables', $variables);
        $appStateManager->override('document-dynamic-variables', []);
        $appStateManager->override('operation-name', $operationName);
        $appStateManager->override('does-api-query-have-errors', null);
        $appStateManager->override('executable-document-ast-field-fragmentmodels-tuples', null);

        // Convert the GraphQL query to AST
        $executableDocument = null;
        $documentASTNodeAncestors = null;
        $documentObjectResolvedFieldValueReferencedFields = [];
        try {
            $graphQLQueryParsingPayload = $this->getGraphQLParserHelperService()->parseGraphQLQuery(
                $query,
                $variables,
                $operationName
            );
            $executableDocument = $graphQLQueryParsingPayload->executableDocument;
            $documentObjectResolvedFieldValueReferencedFields = $graphQLQueryParsingPayload->objectResolvedFieldValueReferencedFields;
        } catch (AbstractASTNodeException $astNodeException) {
            App::getFeedbackStore()->documentFeedbackStore->addError(
                new QueryFeedback(
                    $astNodeException->getFeedbackItemResolution(),
                    $astNodeException->getAstNode(),
                )
            );
        } catch (AbstractASTNodeParserException $astNodeParserException) {
            App::getFeedbackStore()->documentFeedbackStore->addError(
                new QueryFeedback(
                    $astNodeParserException->getFeedbackItemResolution(),
                    $astNodeParserException->getAstNode(),
                )
            );
        } catch (AbstractParserException $parserException) {
            App::getFeedbackStore()->documentFeedbackStore->addError(
                new DocumentFeedback(
                    $parserException->getFeedbackItemResolution(),
                    $parserException->getLocation(),
                )
            );
        }

        $appStateManager->override('document-object-resolved-field-value-referenced-fields', $documentObjectResolvedFieldValueReferencedFields);

        if ($executableDocument !== null) {
            /**
             * Calculate now, as it's useful also if the validation
             * of the ExecutableDocument has errors.
             */
            $documentASTNodeAncestors = $executableDocument->getDocument()->getASTNodeAncestors();

            try {
                $executableDocument->validateAndInitialize();
            } catch (AbstractASTNodeException $astNodeException) {
                $executableDocument = null;
                App::getFeedbackStore()->documentFeedbackStore->addError(
                    new QueryFeedback(
                        $astNodeException->getFeedbackItemResolution(),
                        $astNodeException->getAstNode(),
                    )
                );
            } catch (AbstractQueryException $queryException) {
                $executableDocument = null;
                App::getFeedbackStore()->documentFeedbackStore->addError(
                    new QueryFeedback(
                        $queryException->getFeedbackItemResolution(),
                        $queryException->getAstNode(),
                    )
                );
            }
        }
        $appStateManager->override('executable-document-ast', $executableDocument);
        $appStateManager->override('document-ast-node-ancestors', $documentASTNodeAncestors);

        /**
         * Set the operation type and, based on it, if mutations are supported.
         * If there's an error in `parseGraphQLQuery`, $executableDocument will be null.
         */
        if ($executableDocument === null) {
            $appStateManager->override('does-api-query-have-errors', true);
        }
    }
}
