<?php

declare(strict_types=1);

namespace PoP\CacheControl\TypeResolverDecorators;

use PoP\CacheControl\Facades\CacheControlManagerFacade;
use PoP\CacheControl\TypeResolverDecorators\ConfigurableCacheControlTypeResolverDecoratorTrait;
use PoP\MandatoryDirectivesByConfiguration\TypeResolverDecorators\AbstractMandatoryDirectivesForFieldsTypeResolverDecorator;

class ConfigurableCacheControlForFieldsTypeResolverDecorator extends AbstractMandatoryDirectivesForFieldsTypeResolverDecorator
{
    use ConfigurableCacheControlTypeResolverDecoratorTrait;

    protected static function getConfigurationEntries(): array
    {
        $cacheControlManager = CacheControlManagerFacade::getInstance();
        return $cacheControlManager->getEntriesForFields();
    }
}
