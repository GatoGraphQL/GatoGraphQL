<?php

declare(strict_types=1);

namespace PoP\CacheControl\TypeResolverDecorators;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\CacheControl\Managers\CacheControlManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\CacheControl\TypeResolverDecorators\ConfigurableCacheControlTypeResolverDecoratorTrait;
use PoP\MandatoryDirectivesByConfiguration\TypeResolverDecorators\AbstractMandatoryDirectivesForDirectivesTypeResolverDecorator;

class ConfigurableCacheControlForDirectivesTypeResolverDecorator extends AbstractMandatoryDirectivesForDirectivesTypeResolverDecorator
{
    use ConfigurableCacheControlTypeResolverDecoratorTrait;

    function __construct(
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        protected CacheControlManagerInterface $cacheControlManager,
    ) {
        parent::__construct(
            $instanceManager,
            $fieldQueryInterpreter,
        );
    }

    protected function getConfigurationEntries(): array
    {
        return $this->cacheControlManager->getEntriesForDirectives();
    }
}
