<?php

declare(strict_types=1);

namespace PoP\CacheControl\RelationalTypeResolverDecorators;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\CacheControl\Managers\CacheControlManagerInterface;
use PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators\AbstractMandatoryDirectivesForDirectivesRelationalTypeResolverDecorator;

class ConfigurableCacheControlForDirectivesRelationalTypeResolverDecorator extends AbstractMandatoryDirectivesForDirectivesRelationalTypeResolverDecorator
{
    use ConfigurableCacheControlRelationalTypeResolverDecoratorTrait;

    protected CacheControlManagerInterface $cacheControlManager;

    #[Required]
    public function autowireConfigurableCacheControlForDirectivesRelationalTypeResolverDecorator(
        CacheControlManagerInterface $cacheControlManager,
    ): void {
        $this->cacheControlManager = $cacheControlManager;
    }

    protected function getConfigurationEntries(): array
    {
        return $this->cacheControlManager->getEntriesForDirectives();
    }
}
