<?php

declare(strict_types=1);

namespace PoP\AccessControl\Hooks;

use PoP\AccessControl\ConfigurationEntries\AccessControlConfigurableMandatoryDirectivesForFieldsTrait;
use PoP\AccessControl\Hooks\AccessControlConfigurableMandatoryDirectivesForFieldsHookSetTrait;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\MandatoryDirectivesByConfiguration\ConfigurationEntries\ConfigurableMandatoryDirectivesForFieldsTrait;

abstract class AbstractConfigurableAccessControlForFieldsInPrivateSchemaHookSet extends AbstractAccessControlForFieldsInPrivateSchemaHookSet
{
    use AccessControlConfigurableMandatoryDirectivesForFieldsHookSetTrait;
    use ConfigurableMandatoryDirectivesForFieldsTrait, AccessControlConfigurableMandatoryDirectivesForFieldsTrait {
        AccessControlConfigurableMandatoryDirectivesForFieldsTrait::getMatchingEntries insteadof ConfigurableMandatoryDirectivesForFieldsTrait;
        // The conflict resolutions below should not be needed, because the functions are not repeated, but it is defined just once in the same source trait
        // However, there is a bug about, still unresolved by PHP 7.2: https://bugs.php.net/bug.php?id=63911
        // It was resolved by PHP 7.3.9, though, but handle to add compatibility up to PHP 7.1
        AccessControlConfigurableMandatoryDirectivesForFieldsTrait::getEntries insteadof ConfigurableMandatoryDirectivesForFieldsTrait;
        AccessControlConfigurableMandatoryDirectivesForFieldsTrait::getConfigurationEntries insteadof ConfigurableMandatoryDirectivesForFieldsTrait;
        AccessControlConfigurableMandatoryDirectivesForFieldsTrait::getFieldNames insteadof ConfigurableMandatoryDirectivesForFieldsTrait;
        AccessControlConfigurableMandatoryDirectivesForFieldsTrait::getEntriesByTypeAndInterfaces insteadof ConfigurableMandatoryDirectivesForFieldsTrait;
    }

    protected function enabled(): bool
    {
        return parent::enabled() && !empty($this->getConfigurationEntries());
    }

    /**
     * Remove fieldName "roles" if the user is not logged in
     */
    protected function removeFieldName(
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        ObjectTypeFieldResolverInterface | InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver,
        string $fieldName
    ): bool {
        // Obtain all entries for the current combination of [typeResolver or interfaceTypeResolverClass]/fieldName
        foreach (
            $this->getEntries(
                $objectTypeOrInterfaceTypeResolver,
                $objectTypeOrInterfaceTypeFieldResolver,
                $fieldName
            ) as $entry
        ) {
            // Obtain the 3rd value on each entry: if the validation is "in" or "out"
            $entryValue = $entry[2] ?? null;
            // Let the implementation class decide if to remove the field or not
            return $this->removeFieldNameBasedOnMatchingEntryValue($entryValue);
        }
        return false;
    }

    protected function removeFieldNameBasedOnMatchingEntryValue($entryValue = null): bool
    {
        return true;
    }
}
