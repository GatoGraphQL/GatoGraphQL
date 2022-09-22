<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\QueryResolution;

use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use PoPAPI\API\QueryResolution\QueryASTTransformationService as UpstreamQueryASTTransformationService;
use PoP\ComponentModel\App;
use PoP\ComponentModel\GraphQLParser\ExtendedSpec\Parser\Parser;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\MutationOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\Root\Exception\ShouldNotHappenException;

class QueryASTTransformationService extends UpstreamQueryASTTransformationService implements QueryASTTransformationServiceInterface
{
    /**
     * Convert the operations (query, mutation, subscription) in the
     * GraphQL Documents, to the corresponding field in the SuperRoot
     * type ("queryRoot", "mutationRoot", "subscriptionRoot"), which is
     * the type from which the GraphQL query is resolved.
     *
     * @see layers/GraphQLByPoP/packages/graphql-server/src/ComponentRoutingProcessors/EntryComponentRoutingProcessor.php
     *
     * @param OperationInterface[] $operations
     * @return OperationInterface[]
     */
    public function convertOperationsToContainGraphQLSuperRootFields(array $operations): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $enableNestedMutations = $moduleConfiguration->enableNestedMutations();

        $parser = $this->createParser();
        $convertedOperations = [];
        foreach ($operations as $operation) {
            /**
             * As there is no setFields method, must create
             * a new object for the Query/Mutation Operations.
             *
             * Please notice that support for Operation Directives
             * is handled here, by transferring them into the
             * SuperRoot Field, to be validated and executed
             * there as a standard Field Directive.
             */
            if ($operation instanceof QueryOperation) {
                $convertedOperations[] = $parser->createQueryOperation(
                    $operation->getName(),
                    $operation->getVariables(),
                    $operation->getDirectives(),
                    [
                        new RelationalField(
                            $enableNestedMutations ? 'root' : 'queryRoot',
                            null,
                            [],
                            $operation->getFieldsOrFragmentBonds(),
                            $operation->getDirectives(),
                            ASTNodesFactory::getNonSpecificLocation()
                        ),
                    ],
                    $operation->getLocation()
                );
                continue;
            }
            if ($operation instanceof MutationOperation) {
                $convertedOperations[] = $parser->createMutationOperation(
                    $operation->getName(),
                    $operation->getVariables(),
                    $operation->getDirectives(),
                    [
                        new RelationalField(
                            $enableNestedMutations ? 'root' : 'mutationRoot',
                            null,
                            [],
                            $operation->getFieldsOrFragmentBonds(),
                            $operation->getDirectives(),
                            ASTNodesFactory::getNonSpecificLocation()
                        ),
                    ],
                    $operation->getLocation()
                );
                continue;
            }
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('Cannot recognize GraphQL Operation AST object, with class \'%s\''),
                    get_class($operation)
                )
            );
        }
        return $convertedOperations;
    }
    
    protected function createParser(): ParserInterface
    {
        return new Parser();
    }
}
