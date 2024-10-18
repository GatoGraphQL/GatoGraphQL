<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\ConfigurationEntries\AccessControlConfigurableMandatoryDirectivesForDirectivesTrait;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators\ConfigurableMandatoryDirectivesForDirectivesRelationalTypeResolverDecoratorTrait;

trait ConfigurableAccessControlForDirectivesRelationalTypeResolverDecoratorTrait
{
    use ConfigurableMandatoryDirectivesForDirectivesRelationalTypeResolverDecoratorTrait, AccessControlConfigurableMandatoryDirectivesForDirectivesTrait {
        AccessControlConfigurableMandatoryDirectivesForDirectivesTrait::getMatchingEntries insteadof ConfigurableMandatoryDirectivesForDirectivesRelationalTypeResolverDecoratorTrait;
    }

    /**
     * Because the validation can be done on any directive applied to any typeResolver,
     * then attach it to the base class AbstractRelationalTypeResolver
     * @return array<class-string<RelationalTypeResolverInterface>>
     */
    public function getRelationalTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRelationalTypeResolver::class,
        ];
    }
}
