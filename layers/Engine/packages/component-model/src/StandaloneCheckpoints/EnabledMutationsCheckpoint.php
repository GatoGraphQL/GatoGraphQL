<?php

declare(strict_types=1);

namespace PoP\ComponentModel\StandaloneCheckpoints;

use PoP\ComponentModel\FeedbackItemProviders\CheckpointErrorFeedbackItemProvider;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\MutationOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\RuntimeLocation;
use PoP\Root\App;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use SplObjectStorage;

class EnabledMutationsCheckpoint extends AbstractStandaloneCheckpoint
{
    public function __construct(
        protected FieldInterface $field,
    ) {
    }

    public function validateCheckpoint(): ?FeedbackItemResolution
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableMutations()) {
            return new FeedbackItemResolution(
                CheckpointErrorFeedbackItemProvider::class,
                CheckpointErrorFeedbackItemProvider::E1
            );
        }

        /**
         * Get the Operation for the field, and check
         * that it is a Mutation
         *
         * @var SplObjectStorage<AstInterface,AstInterface>
         */
        $documentASTNodeAncestors = App::getState('document-ast-node-ancestors');
        $astNode = $this->field;
        $location = $astNode->getLocation();
        if ($location instanceof RuntimeLocation) {
            /** @var RuntimeLocation $location */
            $astNode = $location->getStaticASTNode();
        }
        $astNodeTopMostAncestor = null;
        while ($astNode !== null) {
            $astNodeTopMostAncestor = $astNode;
            $astNode = $documentASTNodeAncestors[$astNode] ?? null;
            $location = $astNode?->getLocation();
            if ($location instanceof RuntimeLocation) {
                /** @var RuntimeLocation $location */
                $astNode = $location->getStaticASTNode();
            }
        }
        if ($astNodeTopMostAncestor instanceof OperationInterface) {
            $operation = $astNodeTopMostAncestor;
            if (!($operation instanceof MutationOperation)) {
                return new FeedbackItemResolution(
                    CheckpointErrorFeedbackItemProvider::class,
                    CheckpointErrorFeedbackItemProvider::E2
                );
            }
        }

        return parent::validateCheckpoint();
    }
}
