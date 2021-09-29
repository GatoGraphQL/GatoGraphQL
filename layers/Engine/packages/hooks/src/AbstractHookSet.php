<?php

declare(strict_types=1);

namespace PoP\Hooks;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractHookSet extends AbstractAutomaticallyInstantiatedService
{
    protected HooksAPIInterface $hooksAPI;
    protected TranslationAPIInterface $translationAPI;
    protected InstanceManagerInterface $instanceManager;

    #[Required]
    public function autowireAbstractHookSet(HooksAPIInterface $hooksAPI, TranslationAPIInterface $translationAPI, InstanceManagerInterface $instanceManager): void
    {
        $this->hooksAPI = $hooksAPI;
        $this->translationAPI = $translationAPI;
        $this->instanceManager = $instanceManager;
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
