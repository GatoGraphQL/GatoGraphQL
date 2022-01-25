<?php

declare(strict_types=1);

namespace PoP\Engine\Engine;

use Exception;
use PoP\CacheControl\Component as CacheControlComponent;
use PoP\CacheControl\Managers\CacheControlEngineInterface;
use PoP\ComponentModel\Engine\Engine as UpstreamEngine;
use PoP\LooseContracts\LooseContractManagerInterface;
use PoP\Root\App;

class Engine extends UpstreamEngine implements EngineInterface
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

    public function generateData(): void
    {
        // Check if there are loose contracts that must be implemented by the CMS, that have not been done so.
        if ($notImplementedNames = $this->getLooseContractManager()->getNotImplementedRequiredNames()) {
            throw new Exception(
                sprintf(
                    $this->__('The following names have not been implemented by the CMS: "%s". Hence, we can\'t continue.'),
                    implode($this->__('", "'), $notImplementedNames)
                )
            );
        }

        parent::generateData();
    }

    public function generateDataAndPrepareResponse(): void
    {
        // 1. Generate the data
        $this->generateData();

        // 2. Get the data, and ask the formatter to output it
        $data = $this->getOutputData();
        $dataStructureFormatter = $this->getDataStructureManager()->getDataStructureFormatter();
        $outputContent = $dataStructureFormatter->getOutputContent($data);

        // 3. Prepare the Response
        $response = App::getResponse();
        $response->setContent($outputContent);
        foreach ($this->getHeaders() as $name => $value) {
            $response->headers->set($name, $value);
        }
    }

    /**
     * @return array<string,string>
     */
    protected function getHeaders(): array
    {
        // If CacheControl is enabled, add it to the headers
        $headers = [];
        if (App::getComponent(CacheControlComponent::class)->isEnabled()) {
            if ($cacheControlHeaders = $this->getCacheControlEngine()->getCacheControlHeaders()) {
                $headers = array_merge(
                    $headers,
                    $cacheControlHeaders
                );
            }
        }

        // Add the content type header
        $dataStructureFormatter = $this->getDataStructureManager()->getDataStructureFormatter();
        if ($contentType = $dataStructureFormatter->getContentType()) {
            $headers['Content-type'] = $contentType;
        }
        
        return $headers;
    }
}
