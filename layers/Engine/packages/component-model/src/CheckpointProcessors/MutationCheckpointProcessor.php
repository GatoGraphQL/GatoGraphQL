<?php

declare(strict_types=1);

namespace PoP\ComponentModel\CheckpointProcessors;

use PoP\Root\App;
use PoP\ComponentModel\Error\Error;

class MutationCheckpointProcessor extends AbstractCheckpointProcessor
{
    public const HOOK_MUTATIONS_NOT_SUPPORTED_ERROR_MSG = __CLASS__ . ':MutationsNotSupportedErrorMsg';
    public const ENABLED_MUTATIONS = 'enabled-mutations';

    public function getCheckpointsToProcess(): array
    {
        return array(
            [self::class, self::ENABLED_MUTATIONS],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?Error
    {
        switch ($checkpoint[1]) {
            case self::ENABLED_MUTATIONS:
                if (!App::getState('are-mutations-enabled')) {
                    $errorMessage = $this->getHooksAPI()->applyFilters(
                        self::HOOK_MUTATIONS_NOT_SUPPORTED_ERROR_MSG,
                        $this->__('Mutations cannot be executed', 'component-model')
                    );
                    return new Error(
                        'mutations-not-supported',
                        $errorMessage
                    );
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}
