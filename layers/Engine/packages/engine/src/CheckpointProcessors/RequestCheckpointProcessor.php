<?php

declare(strict_types=1);

namespace PoP\Engine\CheckpointProcessors;

use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;
use PoP\ComponentModel\Error\Error;

class RequestCheckpointProcessor extends AbstractCheckpointProcessor
{
    public const DOING_POST = 'doing-post';

    public function getCheckpointsToProcess(): array
    {
        return array(
            [self::class, self::DOING_POST],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?Error
    {
        switch ($checkpoint[1]) {
            case self::DOING_POST:
                if ('POST' !== \PoP\Root\App::server('REQUEST_METHOD')) {
                    return new Error('notdoingpost');
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}
