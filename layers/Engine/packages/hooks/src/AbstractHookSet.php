<?php

declare(strict_types=1);

namespace PoP\Hooks;

use PoP\Hooks\HooksAPIInterface;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Translation\TranslationAPIInterface;

abstract class AbstractHookSet extends AbstractAutomaticallyInstantiatedService
{
    public function __construct(
        protected HooksAPIInterface $hooksAPI,
        protected TranslationAPIInterface $translationAPI
    ) {
    }

    final public function initialize(): void
    {
        // Initialize the hooks
        $this->init();
    }

    /**
     * Initialize the hooks
     */
    abstract protected function init(): void;
}
