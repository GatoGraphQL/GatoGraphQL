<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\ConfigurationEntries\AccessControlConfigurableMandatoryDirectivesForFieldsTrait;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators\ConfigurableMandatoryDirectivesForFieldsRelationalTypeResolverDecoratorTrait;

trait ConfigurableAccessControlForFieldsRelationalTypeResolverDecoratorTrait
{
    use ConfigurableMandatoryDirectivesForFieldsRelationalTypeResolverDecoratorTrait, AccessControlConfigurableMandatoryDirectivesForFieldsTrait {
        AccessControlConfigurableMandatoryDirectivesForFieldsTrait::getMatchingEntries insteadof ConfigurableMandatoryDirectivesForFieldsRelationalTypeResolverDecoratorTrait;
    }

    public function enabled(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        return parent::enabled($relationalTypeResolver) && !empty($this->getConfigurationEntries());
    }
}
