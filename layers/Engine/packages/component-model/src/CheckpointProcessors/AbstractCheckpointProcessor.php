<?php

declare(strict_types=1);

namespace PoP\ComponentModel\CheckpointProcessors;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;

abstract class AbstractCheckpointProcessor
{
    function __construct(
        protected TranslationAPIInterface $translationAPI,
        protected HooksAPIInterface $hooksAPI
    ) {
    }

    abstract public function getCheckpointsToProcess();

    public function process(array $checkpoint)
    {
        // By default, no problem at all, so always return true
        return true;
    }
}
