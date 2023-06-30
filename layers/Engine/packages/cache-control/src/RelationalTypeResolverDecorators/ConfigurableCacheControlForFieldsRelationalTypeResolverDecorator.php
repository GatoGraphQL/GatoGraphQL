<?php

declare(strict_types=1);

namespace PoP\CacheControl\RelationalTypeResolverDecorators;

use PoP\CacheControl\DirectiveResolvers\CacheControlFieldDirectiveResolver;
use PoP\CacheControl\Managers\CacheControlManagerInterface;
use PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators\AbstractMandatoryDirectivesForFieldsRelationalTypeResolverDecorator;

class ConfigurableCacheControlForFieldsRelationalTypeResolverDecorator extends AbstractMandatoryDirectivesForFieldsRelationalTypeResolverDecorator
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
        if ($this->cacheControlManager === null) {
            /** @var CacheControlManagerInterface */
            $cacheControlManager = $this->instanceManager->getInstance(CacheControlManagerInterface::class);
            $this->cacheControlManager = $cacheControlManager;
        }
        return $this->cacheControlManager;
    }
    final public function setCacheControlFieldDirectiveResolver(CacheControlFieldDirectiveResolver $cacheControlFieldDirectiveResolver): void
    {
        $this->cacheControlFieldDirectiveResolver = $cacheControlFieldDirectiveResolver;
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
        return $this->getCacheControlManager()->getEntriesForFields();
    }
}
