<?php

declare(strict_types=1);

namespace PoP\Engine\CheckpointProcessors;

use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;
use PoP\ComponentModel\ErrorHandling\Error;

class RequestCheckpointProcessor extends AbstractCheckpointProcessor
{
    public const DOING_POST = 'doing-post';

    public function getCheckpointsToProcess()
    {
        return array(
            [self::class, self::DOING_POST],
        );
    }

    public function process(array $checkpoint)
    {
        switch ($checkpoint[1]) {
            case self::DOING_POST:
                if ('POST' != $_SERVER['REQUEST_METHOD']) {
                    return new Error('notdoingpost');
                }
                break;
        }

        return parent::process($checkpoint);
    }
}
