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
     * Because fields are stored in SplObjectStorage,
     * the same instance must be retrieved in every case.
     * Then, cache and reuse every created field
     *
     * @var SplObjectStorage<Document,array<string,RelationalField>>
     */
    private SplObjectStorage $fieldInstanceContainer;

    public function __construct()
    {
        parent::__construct();

        /**
         * @var SplObjectStorage<Document,array<string,RelationalField>>
         */
        $fieldInstanceContainer = new SplObjectStorage();
        $this->fieldInstanceContainer = $fieldInstanceContainer;
    }

    /**
     * @return array<FieldInterface|FragmentBondInterface>
     */
    protected function getOperationFieldsOrFragmentBonds(
        Document $document,
        OperationInterface $operation,
    ): array {
        return [
            $this->getGraphQLSuperRootOperationField(
                $document,
                $operation
            ),
        ];
    }

    /**
     * Convert the operations (query, mutation, subscription) in the
     * GraphQL Documents, to the corresponding field in the SuperRoot
     * type ("queryRoot", "mutationRoot", "subscriptionRoot"), which is
     * the type from which the GraphQL query is resolved.
     *
     * Object caching (via alias) is mandatory:
     * Always return the same object for the same Operation!
     *
     * @see layers/GraphQLByPoP/packages/graphql-server/src/ComponentRoutingProcessors/EntryComponentRoutingProcessor.php
     */
    public function getGraphQLSuperRootOperationField(
        Document $document,
        OperationInterface $operation
    ): FieldInterface {
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

        if ($operation instanceof QueryOperation) {
            $superRootField = $enableNestedMutations ? '_rootForQueryRoot' : '_queryRoot';
        } elseif ($operation instanceof MutationOperation) {
            $superRootField = $enableNestedMutations ? '_rootForMutationRoot' : '_mutationRoot';
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
            $nonSpecificLocation = ASTNodesFactory::getNonSpecificLocation();
            /**
             * Add 2 fields, because validating if a Directive
             * is supported or excluded is executed against the
             * fieldTypeResolver, hence the Field must be of type
             * SuperRoot.
             *
             * Then, the 2 fields are:
             *
             *   1. self: [SuperRoot]
             *   2. root: [Root] or queryRoot: [QueryRoot] or mutationRoot: [MutationRoot]
             *
             * And the Operation Directives are moved to the "self" field,
             * which is of type SuperRoot, so the fieldTypeResolver will
             * be of type SuperRoot.
             *
             * @see layers/Engine/packages/component-model/src/DirectiveResolvers/AbstractFieldDirectiveResolver.php, method `resolveCanProcessFieldBasedOnSupportedFieldTypeResolverClasses`
             */
            $documentFieldInstanceContainer[$alias] = new RelationalField(
                'self',
                $alias . 'self_',
                [],
                [
                    new RelationalField(
                        $superRootField,
                        $alias,
                        [],
                        $operation->getFieldsOrFragmentBonds(),
                        [],
                        $nonSpecificLocation
                    )
                ],
                /**
                 * Support for Operation Directives is handled here,
                 * by transferring them into the SuperRoot Field,
                 * to be validated and executed there as a standard
                 * Field Directive.
                 */
                $operation->getDirectives(),
                $nonSpecificLocation
            );
        }
        $this->fieldInstanceContainer[$document] = $documentFieldInstanceContainer;
        /** @var FieldInterface */
        return $documentFieldInstanceContainer[$alias];
    }

    /**
     * Added 2 extra fields, these must be taken into account
     * when generating the "self" fields for Multiple Query
     * Execution.
     */
    protected function getOperationInitialDepth(): int
    {
        return 2;
    }
}
