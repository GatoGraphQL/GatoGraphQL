<?php

declare(strict_types=1);

namespace PoP\CacheControl\RelationalTypeResolverDecorators;

use PoP\CacheControl\DirectiveResolvers\CacheControlFieldDirectiveResolver;
use PoP\CacheControl\Managers\CacheControlManagerInterface;
use PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators\AbstractMandatoryDirectivesForDirectivesRelationalTypeResolverDecorator;

class ConfigurableCacheControlForDirectivesRelationalTypeResolverDecorator extends AbstractMandatoryDirectivesForDirectivesRelationalTypeResolverDecorator
{
    use ConfigurableCacheControlRelationalTypeResolverDecoratorTrait;

    private ?CacheControlManagerInterface $cacheControlManager = null;
    private ?CacheControlFieldDirectiveResolver $cacheControlFieldDirectiveResolver = null;

    final protected function getCacheControlManager(): CacheControlManagerInterface
    {
        if ($this->cacheControlManager === null) {
            /** @var CacheControlManagerInterface */
            $cacheControlManager = $this->instanceManager->getInstance(CacheControlManagerInterface::class);
            $this->cacheControlManager = $cacheControlManager;
        }
        return $this->cacheControlManager;
    }
    final protected function getCacheControlFieldDirectiveResolver(): CacheControlFieldDirectiveResolver
    {
        if ($this->cacheControlFieldDirectiveResolver === null) {
            /** @var CacheControlFieldDirectiveResolver */
            $cacheControlFieldDirectiveResolver = $this->instanceManager->getInstance(CacheControlFieldDirectiveResolver::class);
            $this->cacheControlFieldDirectiveResolver = $cacheControlFieldDirectiveResolver;
        }
        return $this->cacheControlFieldDirectiveResolver;
    }

    /**
     * @return array<mixed[]>
     */
    protected function getConfigurationEntries(): array
    {
        return $this->getCacheControlManager()->getEntriesForDirectives();
    }
}
