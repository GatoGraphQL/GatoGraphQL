<?php

declare(strict_types=1);

namespace PoP\BasicService;

use PoP\BasicService\BasicServiceTrait;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractHookSet extends AbstractAutomaticallyInstantiatedService
{
    use BasicServiceTrait;

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
