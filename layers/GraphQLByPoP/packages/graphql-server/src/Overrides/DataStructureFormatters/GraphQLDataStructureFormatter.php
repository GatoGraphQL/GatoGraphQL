<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Overrides\DataStructureFormatters;

use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use GraphQLByPoP\GraphQLServer\QueryResolution\GraphQLQueryASTTransformationServiceInterface;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter as UpstreamGraphQLDataStructureFormatter;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
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

    final protected function getGraphQLQueryASTTransformationService(): GraphQLQueryASTTransformationServiceInterface
    {
        if ($this->graphQLQueryASTTransformationService === null) {
            /** @var GraphQLQueryASTTransformationServiceInterface */
            $graphQLQueryASTTransformationService = $this->instanceManager->getInstance(GraphQLQueryASTTransformationServiceInterface::class);
            $this->graphQLQueryASTTransformationService = $graphQLQueryASTTransformationService;
        }
        return $this->graphQLQueryASTTransformationService;
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
        $graphQLQueryASTTransformationService = $this->getGraphQLQueryASTTransformationService();
        $superRootOperationFields = [];
        $document = $executableDocument->getDocument();
        /** @var OperationInterface[] */
        $operations = $executableDocument->getMultipleOperationsToExecute();
        foreach ($operations as $operation) {
            $superRootOperationFields[] = $graphQLQueryASTTransformationService->getGraphQLSuperRootOperationField(
                $document,
                $operation
            );
        }
        return $superRootOperationFields;
    }
}
