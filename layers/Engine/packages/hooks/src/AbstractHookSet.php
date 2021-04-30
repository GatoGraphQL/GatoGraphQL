<?php

declare(strict_types=1);

namespace PoP\Hooks;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractHookSet extends AbstractAutomaticallyInstantiatedService
{
    public function __construct(
        protected HooksAPIInterface $hooksAPI,
        protected TranslationAPIInterface $translationAPI,
        protected InstanceManagerInterface $instanceManager,
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
