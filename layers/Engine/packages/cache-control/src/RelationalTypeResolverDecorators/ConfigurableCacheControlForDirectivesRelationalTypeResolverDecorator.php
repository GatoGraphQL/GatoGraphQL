<?php

declare(strict_types=1);

namespace PoP\CacheControl\RelationalTypeResolverDecorators;

use PoP\CacheControl\DirectiveResolvers\CacheControlDirectiveResolver;
use PoP\CacheControl\Managers\CacheControlManagerInterface;
use PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators\AbstractMandatoryDirectivesForDirectivesRelationalTypeResolverDecorator;
use Symfony\Contracts\Service\Attribute\Required;

class ConfigurableCacheControlForDirectivesRelationalTypeResolverDecorator extends AbstractMandatoryDirectivesForDirectivesRelationalTypeResolverDecorator
{
    use ConfigurableCacheControlRelationalTypeResolverDecoratorTrait;

    private ?CacheControlManagerInterface $cacheControlManager = null;
    private ?CacheControlDirectiveResolver $cacheControlDirectiveResolver = null;

    final public function setCacheControlManager(CacheControlManagerInterface $cacheControlManager): void
    {
        $this->cacheControlManager = $cacheControlManager;
    }
    final protected function getCacheControlManager(): CacheControlManagerInterface
    {
        return $this->cacheControlManager ??= $this->instanceManager->getInstance(CacheControlManagerInterface::class);
    }
    final public function setCacheControlDirectiveResolver(CacheControlDirectiveResolver $cacheControlDirectiveResolver): void
    {
        $this->cacheControlDirectiveResolver = $cacheControlDirectiveResolver;
    }
    final protected function getCacheControlDirectiveResolver(): CacheControlDirectiveResolver
    {
        return $this->cacheControlDirectiveResolver ??= $this->instanceManager->getInstance(CacheControlDirectiveResolver::class);
    }

    protected function getConfigurationEntries(): array
    {
        return $this->getCacheControlManager()->getEntriesForDirectives();
    }
}
