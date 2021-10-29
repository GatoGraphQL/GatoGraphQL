<?php

declare(strict_types=1);

namespace PoP\Hooks;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\Engine\Services\WithHooksAPIServiceTrait;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractHookSet extends AbstractAutomaticallyInstantiatedService
{
    use BasicServiceTrait;
    use WithHooksAPIServiceTrait;
    
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
