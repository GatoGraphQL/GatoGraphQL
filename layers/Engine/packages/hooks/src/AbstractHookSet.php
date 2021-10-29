<?php

declare(strict_types=1);

namespace PoP\Hooks;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Services\WithInstanceManagerServiceTrait;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractHookSet extends AbstractAutomaticallyInstantiatedService
{
    use WithInstanceManagerServiceTrait;
    
    protected ?HooksAPIInterface $hooksAPI = null;
    protected ?TranslationAPIInterface $translationAPI = null;

    public function setHooksAPI(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }
    protected function getHooksAPI(): HooksAPIInterface
    {
        return $this->hooksAPI ??= $this->getInstanceManager()->getInstance(HooksAPIInterface::class);
    }
    public function setTranslationAPI(TranslationAPIInterface $translationAPI): void
    {
        $this->translationAPI = $translationAPI;
    }
    protected function getTranslationAPI(): TranslationAPIInterface
    {
        return $this->translationAPI ??= $this->getInstanceManager()->getInstance(TranslationAPIInterface::class);
    }

    //#[Required]
    final public function autowireAbstractHookSet(HooksAPIInterface $hooksAPI, TranslationAPIInterface $translationAPI): void
    {
        $this->hooksAPI = $hooksAPI;
        $this->translationAPI = $translationAPI;
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
