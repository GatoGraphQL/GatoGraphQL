<?php

declare(strict_types=1);

namespace PoP\Engine\Engine;

use Exception;
use PoP\CacheControl\Component as CacheControlComponent;
use PoP\CacheControl\Managers\CacheControlEngineInterface;
use PoP\ComponentModel\Engine\Engine as UpstreamEngine;
use PoP\LooseContracts\LooseContractManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

class Engine extends UpstreamEngine implements EngineInterface
{
    private ?LooseContractManagerInterface $looseContractManager = null;
    private ?CacheControlEngineInterface $cacheControlEngine = null;

    public function setLooseContractManager(LooseContractManagerInterface $looseContractManager): void
    {
        $this->looseContractManager = $looseContractManager;
    }
    protected function getLooseContractManager(): LooseContractManagerInterface
    {
        return $this->looseContractManager ??= $this->instanceManager->getInstance(LooseContractManagerInterface::class);
    }
    public function setCacheControlEngine(CacheControlEngineInterface $cacheControlEngine): void
    {
        $this->cacheControlEngine = $cacheControlEngine;
    }
    protected function getCacheControlEngine(): CacheControlEngineInterface
    {
        return $this->cacheControlEngine ??= $this->instanceManager->getInstance(CacheControlEngineInterface::class);
    }

    //#[Required]
    final public function autowireEngineEngine(
        LooseContractManagerInterface $looseContractManager,
        CacheControlEngineInterface $cacheControlEngine
    ): void {
        $this->looseContractManager = $looseContractManager;
        $this->cacheControlEngine = $cacheControlEngine;
    }

    public function generateData(): void
    {
        // Check if there are hooks that must be implemented by the CMS, that have not been done so.
        // Check here, since we can't rely on addAction('popcms:init') to check, since we don't know if it was implemented!
        if ($notImplementedHooks = $this->getLooseContractManager()->getNotImplementedRequiredHooks()) {
            throw new Exception(
                sprintf(
                    $this->translationAPI->__('The following hooks have not been implemented by the CMS: "%s". Hence, we can\'t continue.'),
                    implode($this->translationAPI->__('", "'), $notImplementedHooks)
                )
            );
        }
        if ($notImplementedNames = $this->getLooseContractManager()->getNotImplementedRequiredNames()) {
            throw new Exception(
                sprintf(
                    $this->translationAPI->__('The following names have not been implemented by the CMS: "%s". Hence, we can\'t continue.'),
                    implode($this->translationAPI->__('", "'), $notImplementedNames)
                )
            );
        }

        parent::generateData();
    }

    public function outputResponse(): void
    {
        // 1. Generate the data
        $this->generateData();

        // 2. Get the data, and ask the formatter to output it
        $formatter = $this->getDataStructureManager()->getDataStructureFormatter();

        // If CacheControl is enabled, add it to the headers
        $headers = [];
        if (CacheControlComponent::isEnabled()) {
            if ($cacheControlHeader = $this->getCacheControlEngine()->getCacheControlHeader()) {
                $headers[] = $cacheControlHeader;
            }
        }
        $data = $this->getOutputData();
        $formatter->outputResponse($data, $headers);
    }
}
