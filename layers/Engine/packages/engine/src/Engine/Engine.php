<?php

declare(strict_types=1);

namespace PoP\Engine\Engine;

use PoP\CacheControl\Module as CacheControlModule;
use PoP\CacheControl\Managers\CacheControlEngineInterface;
use PoP\ComponentModel\Engine\Engine as UpstreamEngine;
use PoP\Engine\Exception\ContractNotSatisfiedException;
use PoP\LooseContracts\LooseContractManagerInterface;
use PoP\Root\App;

class Engine extends UpstreamEngine
{
    private ?LooseContractManagerInterface $looseContractManager = null;
    private ?CacheControlEngineInterface $cacheControlEngine = null;

    final public function setLooseContractManager(LooseContractManagerInterface $looseContractManager): void
    {
        $this->looseContractManager = $looseContractManager;
    }
    final protected function getLooseContractManager(): LooseContractManagerInterface
    {
        return $this->looseContractManager ??= $this->instanceManager->getInstance(LooseContractManagerInterface::class);
    }
    final public function setCacheControlEngine(CacheControlEngineInterface $cacheControlEngine): void
    {
        $this->cacheControlEngine = $cacheControlEngine;
    }
    final protected function getCacheControlEngine(): CacheControlEngineInterface
    {
        return $this->cacheControlEngine ??= $this->instanceManager->getInstance(CacheControlEngineInterface::class);
    }

    protected function generateData(): void
    {
        // Check if there are loose contracts that must be implemented by the CMS, that have not been done so.
        if ($notImplementedNames = $this->getLooseContractManager()->getNotImplementedRequiredNames()) {
            throw new ContractNotSatisfiedException(
                sprintf(
                    $this->__('The following names have not been implemented by the CMS: "%s". Hence, we can\'t continue.'),
                    implode($this->__('", "'), $notImplementedNames)
                )
            );
        }

        parent::generateData();
    }

    /**
     * @return array<string,string>
     */
    protected function getHeaders(): array
    {
        $headers = parent::getHeaders();

        // If CacheControl is enabled, add it to the headers
        if (App::getModule(CacheControlModule::class)->isEnabled()) {
            if ($cacheControlHeaders = $this->getCacheControlEngine()->getCacheControlHeaders()) {
                $headers = array_merge(
                    $headers,
                    $cacheControlHeaders
                );
            }
        }

        return $headers;
    }
}
