<?php

declare(strict_types=1);

namespace PoP\CacheControl\RelationalTypeResolverDecorators;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\CacheControl\Managers\CacheControlManagerInterface;
use PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators\AbstractMandatoryDirectivesForFieldsRelationalTypeResolverDecorator;

class ConfigurableCacheControlForFieldsRelationalTypeResolverDecorator extends AbstractMandatoryDirectivesForFieldsRelationalTypeResolverDecorator
{
    use ConfigurableCacheControlRelationalTypeResolverDecoratorTrait;

    protected CacheControlManagerInterface $cacheControlManager;

    #[Required]
    public function autowireConfigurableCacheControlForFieldsRelationalTypeResolverDecorator(
        CacheControlManagerInterface $cacheControlManager,
    ): void {
        $this->cacheControlManager = $cacheControlManager;
    }

    protected function getConfigurationEntries(): array
    {
        return $this->cacheControlManager->getEntriesForFields();
    }
}
