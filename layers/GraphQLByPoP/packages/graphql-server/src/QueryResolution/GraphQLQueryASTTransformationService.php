<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\QueryResolution;

use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use PoPAPI\API\QueryResolution\QueryASTTransformationService;
use PoP\ComponentModel\App;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\MutationOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\Root\Exception\ShouldNotHappenException;
use SplObjectStorage;

class GraphQLQueryASTTransformationService extends QueryASTTransformationService implements GraphQLQueryASTTransformationServiceInterface
{
    /**
     * @param OperationInterface[] $operations
     * @param Fragment[] $fragments
     * @return SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>>
     */
    public function prepareOperationFieldAndFragmentBondsForExecution(
        Document $document,
        array $operations,
        array $fragments,
    ): SplObjectStorage {
        $operationFieldAndFragmentBonds = parent::prepareOperationFieldAndFragmentBondsForExecution(
            $document,
            $operations,
            $fragments,
        );
        return $operationFieldAndFragmentBonds;
        return $this->convertOperationsToContainGraphQLSuperRootFields(
            $operationFieldAndFragmentBonds
        );
    }

    /**
     * Convert the operations (query, mutation, subscription) in the
     * GraphQL Documents, to the corresponding field in the SuperRoot
     * type ("queryRoot", "mutationRoot", "subscriptionRoot"), which is
     * the type from which the GraphQL query is resolved.
     *
     * @see layers/GraphQLByPoP/packages/graphql-server/src/ComponentRoutingProcessors/EntryComponentRoutingProcessor.php
     *
     * @param SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>> $operationFieldAndFragmentBonds
     * @return SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>>
     */
    protected function convertOperationsToContainGraphQLSuperRootFields(
        SplObjectStorage $operationFieldAndFragmentBonds,
    ): SplObjectStorage {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $enableNestedMutations = $moduleConfiguration->enableNestedMutations();

        /** @var OperationInterface $operation */
        foreach ($operationFieldAndFragmentBonds as $operation) {
            /** @var array<FieldInterface|FragmentBondInterface> */
            $fieldAndFragmentBonds = $operationFieldAndFragmentBonds[$operation];
            $alias = sprintf(
                '_superRoot_%s_',
                $operation->getName()
            );
            /**
             * Please notice that support for Operation Directives
             * is handled here, by transferring them into the
             * SuperRoot Field, to be validated and executed
             * there as a standard Field Directive.
             */
            if ($operation instanceof QueryOperation) {
                $operationFieldAndFragmentBonds[$operation] = [
                    new RelationalField(
                        $enableNestedMutations ? 'root' : 'queryRoot',
                        $alias,
                        [],
                        $fieldAndFragmentBonds,
                        $operation->getDirectives(),
                        ASTNodesFactory::getNonSpecificLocation()
                    ),
                ];
                continue;
            }
            if ($operation instanceof MutationOperation) {
                $operationFieldAndFragmentBonds[$operation] = [
                    new RelationalField(
                        $enableNestedMutations ? 'root' : 'mutationRoot',
                        $alias,
                        [],
                        $fieldAndFragmentBonds,
                        $operation->getDirectives(),
                        ASTNodesFactory::getNonSpecificLocation()
                    ),
                ];
                continue;
            }
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('Cannot recognize GraphQL Operation AST object, with class \'%s\''),
                    get_class($operation)
                )
            );
        }
        return $operationFieldAndFragmentBonds;
    }
}
