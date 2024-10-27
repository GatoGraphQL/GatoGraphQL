<?php

declare(strict_types=1);

namespace PoP\Engine\ConditionalOnModule\CacheControl\Hooks;

use PoP\CacheControl\Managers\CacheControlEngineInterface;
use PoP\ComponentModel\Engine\EngineHookNames;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class CacheControlHeadersHookSet extends AbstractHookSet
{
    private ?CacheControlEngineInterface $cacheControlEngine = null;

    final protected function getCacheControlEngine(): CacheControlEngineInterface
    {
        if ($this->cacheControlEngine === null) {
            /** @var CacheControlEngineInterface */
            $cacheControlEngine = $this->instanceManager->getInstance(CacheControlEngineInterface::class);
            $this->cacheControlEngine = $cacheControlEngine;
        }
        return $this->cacheControlEngine;
    }

    protected function init(): void
    {
        App::addFilter(
            EngineHookNames::HEADERS,
            $this->addHeaders(...)
        );
    }

    /**
     * @param array<string,string> $headers
     * @return array<string,string>
     */
    public function addHeaders(array $headers): array
    {
        $cacheControlHeaders = $this->getCacheControlEngine()->getCacheControlHeaders();
        if ($cacheControlHeaders === null) {
            return $headers;
        }
        return array_merge(
            $headers,
            $cacheControlHeaders
        );
    }
}
