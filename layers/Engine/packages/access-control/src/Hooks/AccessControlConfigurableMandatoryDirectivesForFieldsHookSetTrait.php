<?php

declare(strict_types=1);

namespace PoP\AccessControl\Hooks;

use PoP\AccessControl\ComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;

trait AccessControlConfigurableMandatoryDirectivesForFieldsHookSetTrait
{
    public function maybeFilterFieldName(
        bool $include,
        RelationalTypeResolverInterface $relationalTypeResolver,
        FieldResolverInterface $fieldResolver,
        array $fieldInterfaceResolverClasses,
        string $fieldName
    ): bool {
        /**
         * If enabling individual control, then check if there is any entry for this field and schema mode
         */
        if (ComponentConfiguration::enableIndividualControlForPublicPrivateSchemaMode()) {
            /**
             * If there are no entries, then exit by returning the original hook value
             */
            if (empty($this->getEntries($relationalTypeResolver, $fieldInterfaceResolverClasses, $fieldName))) {
                return $include;
            }
        }

        /**
         * The parent case deals with the general case
         */
        return parent::maybeFilterFieldName(
            $include,
            $relationalTypeResolver,
            $fieldResolver,
            $fieldInterfaceResolverClasses,
            $fieldName
        );
    }
}
