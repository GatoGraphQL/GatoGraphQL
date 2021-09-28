<?php

declare(strict_types=1);

namespace PoP\ComponentModel\CheckpointProcessors;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;

abstract class AbstractCheckpointProcessor implements CheckpointProcessorInterface
{
    protected TranslationAPIInterface $translationAPI;
    protected HooksAPIInterface $hooksAPI;
    public function __construct(TranslationAPIInterface $translationAPI, HooksAPIInterface $hooksAPI)
    {
        $this->translationAPI = $translationAPI;
        $this->hooksAPI = $hooksAPI;
    }

    /**
     * By default there's no problem
     */
    public function validateCheckpoint(array $checkpoint): ?Error
    {
        return null;
    }
}
