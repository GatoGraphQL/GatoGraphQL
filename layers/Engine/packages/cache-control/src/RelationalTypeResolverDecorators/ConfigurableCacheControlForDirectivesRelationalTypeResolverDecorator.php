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

    final public function setCacheControlManager(CacheControlManagerInterface $cacheControlManager): void
    {
        $this->cacheControlManager = $cacheControlManager;
    }
    final protected function getCacheControlManager(): CacheControlManagerInterface
    {
        /** @var CacheControlManagerInterface */
        return $this->cacheControlManager ??= $this->instanceManager->getInstance(CacheControlManagerInterface::class);
    }
    final public function setCacheControlFieldDirectiveResolver(CacheControlFieldDirectiveResolver $cacheControlFieldDirectiveResolver): void
    {
        $this->cacheControlFieldDirectiveResolver = $cacheControlFieldDirectiveResolver;
    }
    final protected function getCacheControlFieldDirectiveResolver(): CacheControlFieldDirectiveResolver
    {
        /** @var CacheControlFieldDirectiveResolver */
        return $this->cacheControlFieldDirectiveResolver ??= $this->instanceManager->getInstance(CacheControlFieldDirectiveResolver::class);
    }

    /**
     * @return array<mixed[]>
     */
    protected function getConfigurationEntries(): array
    {
        return $this->getCacheControlManager()->getEntriesForDirectives();
    }
}
