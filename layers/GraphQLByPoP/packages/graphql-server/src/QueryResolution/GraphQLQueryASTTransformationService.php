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
     * @return array<FieldInterface|FragmentBondInterface>
     */
    protected function getOperationFieldsOrFragmentBonds(
        Document $document,
        OperationInterface $operation,
    ): array {
        return $this->wrapOperationFieldsOrFragmentBondsInGraphQLSuperRootField(
            $document,
            $operation
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
     * @return array<FieldInterface|FragmentBondInterface>
     */
    protected function wrapOperationFieldsOrFragmentBondsInGraphQLSuperRootField(
        Document $document,
        OperationInterface $operation
    ): array {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $enableNestedMutations = $moduleConfiguration->enableNestedMutations();

        /**
         * The cache must be stored per Document, or otherwise
         * executing multiple PHPUnit tests may access the
         * same cached objects and produce errors.
         *
         * @var array<string,RelationalField>
         */
        $documentFieldInstanceContainer = $this->fieldInstanceContainer[$document] ?? [];

        if ($enableNestedMutations) {
            $superRootField = 'root';
        } elseif ($operation instanceof QueryOperation) {
            $superRootField = 'queryRoot';
        } elseif ($operation instanceof MutationOperation) {
            $superRootField = 'mutationRoot';
        } else {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('Cannot recognize GraphQL Operation AST object, with class \'%s\''),
                    get_class($operation)
                )
            );
        }

        $alias = sprintf(
            '_superRoot_%s_%s_',
            $superRootField,
            $operation->getName()
        );
        if (!isset($documentFieldInstanceContainer[$alias])) {
            $documentFieldInstanceContainer[$alias] = new RelationalField(
                $superRootField,
                $alias,
                [],
                $operation->getFieldsOrFragmentBonds(),
                /**
                 * Please notice that support for Operation Directives
                 * is handled here, by transferring them into the
                 * SuperRoot Field, to be validated and executed
                 * there as a standard Field Directive.
                 */                
                $operation->getDirectives(),
                ASTNodesFactory::getNonSpecificLocation()
            );
        }
        $this->fieldInstanceContainer[$document] = $documentFieldInstanceContainer;
        /** @var array<FieldInterface|FragmentBondInterface> */
        return [
            $documentFieldInstanceContainer[$alias],
        ];
    }
}
