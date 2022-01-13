<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\Hooks;

use PoP\Root\App;
use GraphQLByPoP\GraphQLQuery\Schema\OperationTypes;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\ComponentModel\CheckpointProcessors\MutationCheckpointProcessor;

class VarsHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        // Change the error message when mutations are not supported
        App::getHookManager()->addFilter(
            MutationCheckpointProcessor::HOOK_MUTATIONS_NOT_SUPPORTED_ERROR_MSG,
            array($this, 'getMutationsNotSupportedErrorMessage'),
            10,
            1
        );
    }

    /**
     * Override the error message when executing a query through standard GraphQL
     */
    public function getMutationsNotSupportedErrorMessage(string $errorMessage): string
    {
        if (App::getState('standard-graphql')) {
            return sprintf(
                $this->__('Use the operation type \'%s\' to execute mutations', 'graphql-request'),
                OperationTypes::MUTATION
            );
        }
        return $errorMessage;
    }
}
