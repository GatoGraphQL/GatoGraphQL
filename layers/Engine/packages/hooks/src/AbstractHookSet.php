<?php

declare(strict_types=1);

namespace PoP\Hooks;

use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use Symfony\Contracts\Service\Attribute\Required;

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
