<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Overrides\DataStructureFormatters;

use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use GraphQLByPoP\GraphQLServer\QueryResolution\GraphQLQueryASTTransformationServiceInterface;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter as UpstreamGraphQLDataStructureFormatter;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;

/**
 * Change the properties printed for the standard GraphQL response:
 *
 * - extension "entityTypeOutputKey" is renamed as "type"
 * - extension "fields" (or "field" if there's one item) instead of "path",
 *   because there are no composable fields
 * - move "location" up from under "extensions"
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class GraphQLDataStructureFormatter extends UpstreamGraphQLDataStructureFormatter
{
    private ?GraphQLQueryASTTransformationServiceInterface $graphQLQueryASTTransformationService = null;

    final public function setGraphQLQueryASTTransformationService(GraphQLQueryASTTransformationServiceInterface $graphQLQueryASTTransformationService): void
    {
        $this->graphQLQueryASTTransformationService = $graphQLQueryASTTransformationService;
    }
    final protected function getGraphQLQueryASTTransformationService(): GraphQLQueryASTTransformationServiceInterface
    {
        /** @var GraphQLQueryASTTransformationServiceInterface */
        return $this->graphQLQueryASTTransformationService ??= $this->instanceManager->getInstance(GraphQLQueryASTTransformationServiceInterface::class);
    }
    
    /**
     * Indicate if to add entry "extensions" as a top-level entry
     */
    protected function addTopLevelExtensionsEntryToResponse(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->enableProactiveFeedback();
    }

    /**
     * Watch out! For GraphQL, the query (or mutation) fields in the AST
     * were wrapped with a RelationalField('queryRoot'),
     * so the initial Type being handled has changed, from
     * QueryRoot to SuperRoot. So for this particular case,
     * the Field comes from the Transformation Service, and not
     * from the AST.
     *
     * @return FieldInterface[]
     */
    protected function getFieldsFromExecutableDocument(
        ExecutableDocument $executableDocument,
    ): array {
        /**
         * For the requested operation, retrieve its Field
         * from the Query Transformation Service, which is
         * storing the SuperRoot field.
         */
        $operationFieldAndFragmentBonds = $this->getGraphQLQueryASTTransformationService()->prepareOperationFieldAndFragmentBondsForExecution(
            $executableDocument->getDocument(),
            $executableDocument->getRequestedOperations(),
            $executableDocument->getDocument()->getFragments(),
        );

        $fieldsFromExecutableDocument = [];
        foreach ($executableDocument->getRequestedOperations() as $operation) {
            // @todo Remove `?? []` after removing __ALL, since that's the only case that is needed
            /** @var FieldInterface[] */
            $operationFields = $operationFieldAndFragmentBonds[$operation] ?? [];
            $fieldsFromExecutableDocument = [
                ...$fieldsFromExecutableDocument,
                ...$operationFields
            ];
        }
        return $fieldsFromExecutableDocument;
    }
}
