<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\Hooks;

use PoPSchema\UserRolesAccessControl\Helpers\UserRoleHelper;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\AccessControl\ConfigurationEntries\AccessControlConfigurableMandatoryDirectivesForFieldsTrait;
use PoP\MandatoryDirectivesByConfiguration\ConfigurationEntries\ConfigurableMandatoryDirectivesForFieldsTrait;
use PoPSchema\UserStateAccessControl\Hooks\AbstractDisableFieldsIfUserIsNotLoggedInAccessControlForFieldsInPrivateSchemaHookSet;

class MaybeDisableFieldsIfLoggedInUserDoesNotHaveRolePrivateSchemaHookSet extends AbstractDisableFieldsIfUserIsNotLoggedInAccessControlForFieldsInPrivateSchemaHookSet
{
    use ConfigurableMandatoryDirectivesForFieldsTrait, AccessControlConfigurableMandatoryDirectivesForFieldsTrait {
        AccessControlConfigurableMandatoryDirectivesForFieldsTrait::getMatchingEntries insteadof ConfigurableMandatoryDirectivesForFieldsTrait;
        // The conflict resolutions below should not be needed, because the functions are not repeated, but it is defined just once in the same source trait
        // However, there is a bug about, still unresolved by PHP 7.2: https://bugs.php.net/bug.php?id=63911
        // It was resolved by PHP 7.3.9, though, but handle to add compatibility up to PHP 7.1
        AccessControlConfigurableMandatoryDirectivesForFieldsTrait::getConfigurationEntries insteadof ConfigurableMandatoryDirectivesForFieldsTrait;
        AccessControlConfigurableMandatoryDirectivesForFieldsTrait::getEntries insteadof ConfigurableMandatoryDirectivesForFieldsTrait;
        AccessControlConfigurableMandatoryDirectivesForFieldsTrait::getFieldNames insteadof ConfigurableMandatoryDirectivesForFieldsTrait;
    }

    protected function enabled(): bool
    {
        return parent::enabled() && !empty($this->getConfigurationEntries());
    }

    /**
     * Configuration entries
     *
     * @return array
     */
    protected function getConfigurationEntries(): array
    {
        $accessControlManager = AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForFields(AccessControlGroups::ROLES);
    }

    /**
     * Decide if to remove the fieldNames
     *
     * @param TypeResolverInterface $typeResolver
     * @param FieldResolverInterface $fieldResolver
     * @param string $fieldName
     * @return boolean
     */
    protected function removeFieldName(
        TypeResolverInterface $typeResolver,
        FieldResolverInterface $fieldResolver,
        array $fieldInterfaceResolverClasses,
        string $fieldName
    ): bool {
        // If the user is not logged in, then remove the field
        $isUserLoggedIn = $this->isUserLoggedIn();
        if (!$isUserLoggedIn) {
            return true;
        }

        // Obtain all roles allowed for the current combination of typeResolver/fieldName
        if (
            $matchingEntries = $this->getEntries(
                $typeResolver,
                $fieldInterfaceResolverClasses,
                $fieldName
            )
        ) {
            foreach ($matchingEntries as $entry) {
                // Check if the current user has any of the required roles, then access is granted, otherwise reject it
                $roles = $entry[2] ?? [];
                if (!UserRoleHelper::doesCurrentUserHaveAnyRole($roles)) {
                    return true;
                }
            }
        }
        return false;
    }
}
