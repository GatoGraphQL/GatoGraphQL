<?php

declare(strict_types=1);

namespace PoP\CacheControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\CacheControl\Managers\CacheControlManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\CacheControl\RelationalTypeResolverDecorators\ConfigurableCacheControlRelationalTypeResolverDecoratorTrait;
use PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators\AbstractMandatoryDirectivesForDirectivesRelationalTypeResolverDecorator;

class ConfigurableCacheControlForDirectivesRelationalTypeResolverDecorator extends AbstractMandatoryDirectivesForDirectivesRelationalTypeResolverDecorator
{
    use ConfigurableCacheControlRelationalTypeResolverDecoratorTrait;
    protected CacheControlManagerInterface $cacheControlManager;
    
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function autowireConfigurableCacheControlForDirectivesRelationalTypeResolverDecorator(
        CacheControlManagerInterface $cacheControlManager,
    ) {
        $this->cacheControlManager = $cacheControlManager;
    }

    protected function getConfigurationEntries(): array
    {
        return $this->cacheControlManager->getEntriesForDirectives();
    }
}
