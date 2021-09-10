<?php

declare(strict_types=1);

namespace PoP\AccessControl\Hooks;

use PoP\AccessControl\ComponentConfiguration;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\Interface\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\Object\ObjectTypeResolverInterface;

trait AccessControlConfigurableMandatoryDirectivesForFieldsHookSetTrait
{
    public function maybeFilterFieldName(
        bool $include,
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        FieldResolverInterface $fieldResolver,
        array $interfaceTypeResolverClasses,
        string $fieldName
    ): bool {
        /**
         * If enabling individual control, then check if there is any entry for this field and schema mode
         */
        if (ComponentConfiguration::enableIndividualControlForPublicPrivateSchemaMode()) {
            /**
             * If there are no entries, then exit by returning the original hook value
             */
            if (empty($this->getEntries(
                $objectTypeOrInterfaceTypeResolver,
                $interfaceTypeResolverClasses,
                $fieldName
            ))) {
                return $include;
            }
        }

        /**
         * The parent case deals with the general case
         */
        return parent::maybeFilterFieldName(
            $include,
            $objectTypeOrInterfaceTypeResolver,
            $fieldResolver,
            $interfaceTypeResolverClasses,
            $fieldName
        );
    }
}
