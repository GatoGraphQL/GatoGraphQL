<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\AccessControl\ConfigurationEntries\AccessControlConfigurableMandatoryDirectivesForFieldsTrait;
use PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators\ConfigurableMandatoryDirectivesForFieldsRelationalTypeResolverDecoratorTrait;

trait ConfigurableAccessControlForFieldsRelationalTypeResolverDecoratorTrait
{
    use ConfigurableMandatoryDirectivesForFieldsRelationalTypeResolverDecoratorTrait, AccessControlConfigurableMandatoryDirectivesForFieldsTrait {
        AccessControlConfigurableMandatoryDirectivesForFieldsTrait::getMatchingEntries insteadof ConfigurableMandatoryDirectivesForFieldsRelationalTypeResolverDecoratorTrait;
        // The conflict resolutions below should not be needed, because the functions are not repeated, but it is defined just once in the same source trait
        // However, there is a bug about, still unresolved by PHP 7.2: https://bugs.php.net/bug.php?id=63911
        // It was resolved by PHP 7.3.9, though, but handle to add compatibility up to PHP 7.1
        AccessControlConfigurableMandatoryDirectivesForFieldsTrait::getConfigurationEntries insteadof ConfigurableMandatoryDirectivesForFieldsRelationalTypeResolverDecoratorTrait;
        AccessControlConfigurableMandatoryDirectivesForFieldsTrait::getEntries insteadof ConfigurableMandatoryDirectivesForFieldsRelationalTypeResolverDecoratorTrait;
        AccessControlConfigurableMandatoryDirectivesForFieldsTrait::getFieldNames insteadof ConfigurableMandatoryDirectivesForFieldsRelationalTypeResolverDecoratorTrait;
    }

    public function enabled(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        return parent::enabled($relationalTypeResolver) && !empty($this->getConfigurationEntries());
    }
}
