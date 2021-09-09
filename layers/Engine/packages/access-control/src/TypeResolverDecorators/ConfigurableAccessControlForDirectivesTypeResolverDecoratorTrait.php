<?php

declare(strict_types=1);

namespace PoP\AccessControl\TypeResolverDecorators;

use PoP\AccessControl\ConfigurationEntries\AccessControlConfigurableMandatoryDirectivesForDirectivesTrait;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\MandatoryDirectivesByConfiguration\TypeResolverDecorators\ConfigurableMandatoryDirectivesForDirectivesTypeResolverDecoratorTrait;

trait ConfigurableAccessControlForDirectivesTypeResolverDecoratorTrait
{
    use ConfigurableMandatoryDirectivesForDirectivesTypeResolverDecoratorTrait, AccessControlConfigurableMandatoryDirectivesForDirectivesTrait {
        AccessControlConfigurableMandatoryDirectivesForDirectivesTrait::getMatchingEntries insteadof ConfigurableMandatoryDirectivesForDirectivesTypeResolverDecoratorTrait;
        // The conflict resolutions below should not be needed, because the functions are not repeated, but it is defined just once in the same source trait
        // However, there is a bug about, still unresolved by PHP 7.2: https://bugs.php.net/bug.php?id=63911
        // It was resolved by PHP 7.3.9, though, but handle to add compatibility up to PHP 7.1
        AccessControlConfigurableMandatoryDirectivesForDirectivesTrait::getEntries insteadof ConfigurableMandatoryDirectivesForDirectivesTypeResolverDecoratorTrait;
        AccessControlConfigurableMandatoryDirectivesForDirectivesTrait::getConfigurationEntries insteadof ConfigurableMandatoryDirectivesForDirectivesTypeResolverDecoratorTrait;
        AccessControlConfigurableMandatoryDirectivesForDirectivesTrait::getRequiredEntryValue insteadof ConfigurableMandatoryDirectivesForDirectivesTypeResolverDecoratorTrait;
        AccessControlConfigurableMandatoryDirectivesForDirectivesTrait::getDirectiveResolverClasses insteadof ConfigurableMandatoryDirectivesForDirectivesTypeResolverDecoratorTrait;
    }

    /**
     * Because the validation can be done on any directive applied to any typeResolver,
     * then attach it to the base class AbstractRelationalTypeResolver
     */
    public function getRelationalTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRelationalTypeResolver::class,
        ];
    }
}
