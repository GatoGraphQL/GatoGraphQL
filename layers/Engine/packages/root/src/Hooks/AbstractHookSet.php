<?php

declare(strict_types=1);

namespace PoP\Root\Hooks;

use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractHookSet extends AbstractAutomaticallyInstantiatedService
{
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
