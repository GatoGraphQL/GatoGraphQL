<?php

declare(strict_types=1);

namespace PoP\CacheControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\CacheControl\Managers\CacheControlManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\CacheControl\RelationalTypeResolverDecorators\ConfigurableCacheControlRelationalTypeResolverDecoratorTrait;
use PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators\AbstractMandatoryDirectivesForFieldsRelationalTypeResolverDecorator;

class ConfigurableCacheControlForFieldsRelationalTypeResolverDecorator extends AbstractMandatoryDirectivesForFieldsRelationalTypeResolverDecorator
{
    use ConfigurableCacheControlRelationalTypeResolverDecoratorTrait;
    protected CacheControlManagerInterface $cacheControlManager;

    public function __construct(
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        CacheControlManagerInterface $cacheControlManager,
    ) {
        $this->cacheControlManager = $cacheControlManager;
        }

    protected function getConfigurationEntries(): array
    {
        return $this->cacheControlManager->getEntriesForFields();
    }
}
