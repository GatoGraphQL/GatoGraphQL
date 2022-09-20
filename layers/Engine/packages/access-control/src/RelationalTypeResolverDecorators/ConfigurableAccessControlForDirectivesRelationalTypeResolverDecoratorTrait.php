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
        // The conflict resolutions below should not be needed, because the functions are not repeated, but it is defined just once in the same source trait
        // However, there is a bug about, still unresolved by PHP 7.2: https://bugs.php.net/bug.php?id=63911
        // It was resolved by PHP 7.3.9, though, but handle to add compatibility up to PHP 7.1
        AccessControlConfigurableMandatoryDirectivesForDirectivesTrait::getEntries insteadof ConfigurableMandatoryDirectivesForDirectivesRelationalTypeResolverDecoratorTrait;
        AccessControlConfigurableMandatoryDirectivesForDirectivesTrait::getRequiredEntryValue insteadof ConfigurableMandatoryDirectivesForDirectivesRelationalTypeResolverDecoratorTrait;
        AccessControlConfigurableMandatoryDirectivesForDirectivesTrait::getFieldDirectiveResolvers insteadof ConfigurableMandatoryDirectivesForDirectivesRelationalTypeResolverDecoratorTrait;
        AccessControlConfigurableMandatoryDirectivesForDirectivesTrait::getFieldDirectiveResolverClasses insteadof ConfigurableMandatoryDirectivesForDirectivesRelationalTypeResolverDecoratorTrait;
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
