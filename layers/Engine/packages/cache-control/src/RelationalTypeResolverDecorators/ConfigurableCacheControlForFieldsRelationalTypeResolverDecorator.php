<?php

declare(strict_types=1);

namespace PoP\CacheControl\RelationalTypeResolverDecorators;

use PoP\CacheControl\Managers\CacheControlManagerInterface;
use PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators\AbstractMandatoryDirectivesForFieldsRelationalTypeResolverDecorator;
use Symfony\Contracts\Service\Attribute\Required;

class ConfigurableCacheControlForFieldsRelationalTypeResolverDecorator extends AbstractMandatoryDirectivesForFieldsRelationalTypeResolverDecorator
{
    use ConfigurableCacheControlRelationalTypeResolverDecoratorTrait;

    private ?CacheControlManagerInterface $cacheControlManager = null;

    public function setCacheControlManager(CacheControlManagerInterface $cacheControlManager): void
    {
        $this->cacheControlManager = $cacheControlManager;
    }
    protected function getCacheControlManager(): CacheControlManagerInterface
    {
        return $this->cacheControlManager ??= $this->instanceManager->getInstance(CacheControlManagerInterface::class);
    }

    protected function getConfigurationEntries(): array
    {
        return $this->getCacheControlManager()->getEntriesForFields();
    }
}
