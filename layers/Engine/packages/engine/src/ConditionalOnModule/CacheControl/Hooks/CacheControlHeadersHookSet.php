<?php

declare(strict_types=1);

namespace PoP\Engine\ConditionalOnModule\CacheControl\Hooks;

use PoP\CacheControl\Managers\CacheControlEngineInterface;
use PoP\ComponentModel\Engine\Engine;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class CacheControlHeadersHookSet extends AbstractHookSet
{
    private ?CacheControlEngineInterface $cacheControlEngine = null;

    final public function setCacheControlEngine(CacheControlEngineInterface $cacheControlEngine): void
    {
        $this->cacheControlEngine = $cacheControlEngine;
    }
    final protected function getCacheControlEngine(): CacheControlEngineInterface
    {
        /** @var CacheControlEngineInterface */
        return $this->cacheControlEngine ??= $this->instanceManager->getInstance(CacheControlEngineInterface::class);
    }

    protected function init(): void
    {
        App::addFilter(
            Engine::HOOK_HEADERS,
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
