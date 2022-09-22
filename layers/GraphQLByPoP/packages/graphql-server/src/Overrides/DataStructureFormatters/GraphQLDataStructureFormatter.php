<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Overrides\DataStructureFormatters;

use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use GraphQLByPoP\GraphQLServer\QueryResolution\GraphQLQueryASTTransformationServiceInterface;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SuperRootObjectTypeResolver;
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
    private ?SuperRootObjectTypeResolver $superRootObjectTypeResolver = null;

    final public function setGraphQLQueryASTTransformationService(GraphQLQueryASTTransformationServiceInterface $graphQLQueryASTTransformationService): void
    {
        $this->graphQLQueryASTTransformationService = $graphQLQueryASTTransformationService;
    }
    final protected function getGraphQLQueryASTTransformationService(): GraphQLQueryASTTransformationServiceInterface
    {
        /** @var GraphQLQueryASTTransformationServiceInterface */
        return $this->graphQLQueryASTTransformationService ??= $this->instanceManager->getInstance(GraphQLQueryASTTransformationServiceInterface::class);
    }
    final public function setSuperRootObjectTypeResolver(SuperRootObjectTypeResolver $superRootObjectTypeResolver): void
    {
        $this->superRootObjectTypeResolver = $superRootObjectTypeResolver;
    }
    final protected function getSuperRootObjectTypeResolver(): SuperRootObjectTypeResolver
    {
        /** @var SuperRootObjectTypeResolver */
        return $this->superRootObjectTypeResolver ??= $this->instanceManager->getInstance(SuperRootObjectTypeResolver::class);
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
        $superRootOperationFields = [];
        foreach ($executableDocument->getRequestedOperations() as $operation) {
            $superRootOperationFields[] = $this->getGraphQLQueryASTTransformationService()->getGraphQLSuperRootOperationField(
                $executableDocument->getDocument(),
                $operation
            );
        }
        return $superRootOperationFields;
    }

    /**
     * The SuperRoot type, for the first field (and others via
     * Multiple Query Execution), must not be printed to the response
     */
    protected function skipAddingDataForType(string $typeOutputKey): bool
    {
        if ($typeOutputKey === $this->getSuperRootObjectTypeResolver()->getTypeOutputKey()) {
            return true;
        }
        return parent::skipAddingDataForType($typeOutputKey);
    }
}
