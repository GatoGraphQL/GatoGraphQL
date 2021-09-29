<?php

declare(strict_types=1);

namespace PoP\Engine\Engine;

use Symfony\Contracts\Service\Attribute\Required;
use Exception;
use PoP\CacheControl\Component as CacheControlComponent;
use PoP\CacheControl\Managers\CacheControlEngineInterface;
use PoP\LooseContracts\LooseContractManagerInterface;
use PoP\ComponentModel\Engine\Engine as UpstreamEngine;

class Engine extends UpstreamEngine implements EngineInterface
{
    protected LooseContractManagerInterface $looseContractManager;
    protected CacheControlEngineInterface $cacheControlEngine;

    #[Required]
    public function autowireEngineEngine(
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
        if ($notImplementedHooks = $this->looseContractManager->getNotImplementedRequiredHooks()) {
            throw new Exception(
                sprintf(
                    $this->translationAPI->__('The following hooks have not been implemented by the CMS: "%s". Hence, we can\'t continue.'),
                    implode($this->translationAPI->__('", "'), $notImplementedHooks)
                )
            );
        }
        if ($notImplementedNames = $this->looseContractManager->getNotImplementedRequiredNames()) {
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
        $formatter = $this->dataStructureManager->getDataStructureFormatter();

        // If CacheControl is enabled, add it to the headers
        $headers = [];
        if (CacheControlComponent::isEnabled()) {
            if ($cacheControlHeader = $this->cacheControlEngine->getCacheControlHeader()) {
                $headers[] = $cacheControlHeader;
            }
        }
        $data = $this->getOutputData();
        $formatter->outputResponse($data, $headers);
    }
}
