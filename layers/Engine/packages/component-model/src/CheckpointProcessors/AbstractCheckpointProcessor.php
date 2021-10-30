<?php

declare(strict_types=1);

namespace PoP\ComponentModel\CheckpointProcessors;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\Hooks\Services\WithHooksAPIServiceTrait;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractCheckpointProcessor implements CheckpointProcessorInterface
{
    use BasicServiceTrait;

    /**
     * By default there's no problem
     */
    public function validateCheckpoint(array $checkpoint): ?Error
    {
        return null;
    }
}
