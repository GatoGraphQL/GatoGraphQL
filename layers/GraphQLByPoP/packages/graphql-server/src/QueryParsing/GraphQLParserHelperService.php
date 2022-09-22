<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\QueryParsing;

use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use PoPAPI\API\QueryParsing\GraphQLParserHelperService as UpstreamGraphQLParserHelperService;
use PoP\ComponentModel\App;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\MutationOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\ParserInterface;
use PoP\Root\Exception\ShouldNotHappenException;

class GraphQLParserHelperService extends UpstreamGraphQLParserHelperService implements GraphQLParserHelperServiceInterface
{
    protected function parseQuery(ParserInterface $parser, string $query): Document
    {
        return $this->convertOperationsToSuperRootFieldsInAST(
            parent::parseQuery($parser, $query)
        );
    }

    /**
     * Convert the operations (query, mutation, subscription) in the
     * GraphQL Documents, to the corresponding field in the SuperRoot
     * type ("queryRoot", "mutationRoot", "subscriptionRoot"), which is
     * the type from which the GraphQL query is resolved.
     *
     * @see layers/GraphQLByPoP/packages/graphql-server/src/ComponentRoutingProcessors/EntryComponentRoutingProcessor.php
     */
    public function convertOperationsToSuperRootFieldsInAST(Document $document): Document
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $enableNestedMutations = $moduleConfiguration->enableNestedMutations();

        $parser = $this->createParser();
        $operations = [];
        foreach ($document->getOperations() as $operation) {
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
                $operations[] = $parser->createQueryOperation(
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
                $operations[] = $parser->createMutationOperation(
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
        return $parser->createDocument(
            $operations,
            $document->getFragments()
        );
    }
}
